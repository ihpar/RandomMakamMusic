function SongBuilder(songFileName)

    % globals

    % sampling rate
    Fs = 44100;
    
    % IR Reverb file
    [IR_Reverb, ~] = audioread('rev_sig.wav');
    [IR_Reverb_Master, ~] = audioread('master_rev_sig.wav');

    % directories
    InstrumentsDir = 'Instruments';
    PercussionDir = strcat(InstrumentsDir, '/', 'Percussion');

    % open song file and read info
    fid = fopen(songFileName);

    % read makam & tempo info
    MakamTempo = strsplit(fgets(fid), ' ');
    Makam = MakamTempo(1);
    Tempo = str2double(MakamTempo(2));

    % time calc
    beatPerSecond = Tempo/60;
    timeUnit = (Fs/beatPerSecond)/4;

    % read desired percussion list
    Percussions = strsplit(fgetl(fid), ' ');
    % read desired instrument list
    Instruments = strsplit(fgetl(fid), ' ');
    % read song notes
    NoteLine = fgetl(fid);
    Notes = strsplit(NoteLine, ' ');
    % read note durations
    Durations = str2double(strsplit(fgetl(fid), ' '));

    % done with the file
    fclose(fid);

    % load percussion files
    percussionCount = length(Percussions);
    PercussionArr = cell(percussionCount:1);
    FileName = '';
    for i = 1:percussionCount
        FileName = strcat(PercussionDir, '/', num2str(Tempo), '-', Percussions{i}, '.wav');
        [PercussionArr{i}, ~] = audioread(FileName);
    end


    % load instrument sound files
    InstrumentArr = cell(length(Instruments):1);
    InstrumentDir = '';
    for i = 1:length(Instruments)
        InstrumentDir = strcat(InstrumentsDir, '/', Makam, '/', Instruments{i});
        InstrumentArr{i} = Instrument(Instruments{i}, Makam, InstrumentDir{1});  
    end

    % create song data for each instrument
    instrumentCount = length(InstrumentArr);
    allInstrumentNotes = cell(instrumentCount:1);
    WetDry = 0.2;
    for i = 1:instrumentCount

        % load notes of instrument
        allInstrumentNotes{i} = [];
        for j = 1:length(Notes)
            allInstrumentNotes{i} = [allInstrumentNotes{i}, InstrumentArr{i}.GetNoteData(Notes(j), ceil(Durations(j)*timeUnit))];
        end

        if strcmpi(InstrumentArr{i}.Name, 'Ney')
            WetDry = 0.6;
        else 
            WetDry = 0.2;
        end
        ReverbedSignal = FastConv(allInstrumentNotes{i}', IR_Reverb);

        allInstrumentNotes{i} = [allInstrumentNotes{i}, zeros(1, length(ReverbedSignal) - length(allInstrumentNotes{i}))];
        allInstrumentNotes{i} = allInstrumentNotes{i}.*(1-WetDry) + ReverbedSignal.*WetDry;

        if strcmpi(InstrumentArr{i}.Name, 'Ney')
            allInstrumentNotes{i} = allInstrumentNotes{i}.*0.8;
        elseif strcmpi(InstrumentArr{i}.Name, 'Kanun')
            allInstrumentNotes{i} = allInstrumentNotes{i}.*0.8;
        elseif strcmpi(InstrumentArr{i}.Name, 'Ud')
            allInstrumentNotes{i} = allInstrumentNotes{i}.*1.5;
        elseif strcmpi(InstrumentArr{i}.Name, 'Tanbur')
            allInstrumentNotes{i} = allInstrumentNotes{i}.*1.2;
        end
    end

    % adjust each instruments length to each other
    longestInstrumentDuration = 0;
    for i = 1:instrumentCount
        if length(allInstrumentNotes{i}) > longestInstrumentDuration
           longestInstrumentDuration = length(allInstrumentNotes{i});
        end
    end

    for i = 1:instrumentCount
        difference = longestInstrumentDuration - length(allInstrumentNotes{i});
        allInstrumentNotes{i} = [allInstrumentNotes{i}, zeros(1, difference)];
    end

    % stereo placement of instruments
    CombinedInstruments = [;];
    if instrumentCount == 1 % 1 instrument
        CombinedInstruments = Panner([allInstrumentNotes{1} ; allInstrumentNotes{1}], 0);
    elseif instrumentCount == 2 % 2 instruments...
        CombinedInstruments = Panner([allInstrumentNotes{1} ; allInstrumentNotes{1}], -15) + ...
        Panner([allInstrumentNotes{2} ; allInstrumentNotes{2}], 15);   
    elseif instrumentCount == 3
        CombinedInstruments = Panner([allInstrumentNotes{1} ; allInstrumentNotes{1}], -30) + ...
        Panner([allInstrumentNotes{2} ; allInstrumentNotes{2}], -5) + ...
        Panner([allInstrumentNotes{3} ; allInstrumentNotes{3}], 35); 
    elseif instrumentCount == 4
        CombinedInstruments = Panner([allInstrumentNotes{1} ; allInstrumentNotes{1}], -35) + ...
        Panner([allInstrumentNotes{2} ; allInstrumentNotes{2}], 35) + ...
        Panner([allInstrumentNotes{3} ; allInstrumentNotes{3}], -15) + ...
        Panner([allInstrumentNotes{4} ; allInstrumentNotes{4}], 15);
    end

    % beautify results
    CombinedInstruments = CombinedInstruments';

    % normalize instrument sound levels
    [Max, ~] = max(abs(CombinedInstruments(:)));
    CombinedInstruments = CombinedInstruments/Max;

    SongSampleLength = length(CombinedInstruments);

    % adjust percussions
    CombinedPercussions = PercussionArr{1};

    for i = 2:percussionCount
        CombinedPercussions = CombinedPercussions + PercussionArr{i};
    end

    % fadeout & normalize percussions
    PercFadeoutEnvelope = fliplr(0:1/(Fs*2):1);
    PercFadeoutEnvelope = [PercFadeoutEnvelope; PercFadeoutEnvelope]';
    CombinedPercussions = CombinedPercussions(1:SongSampleLength,:);
    CombinedPercussionsStart = CombinedPercussions(1:end-length(PercFadeoutEnvelope),:);
    CombinedPercussionsEnd = CombinedPercussions(end-length(PercFadeoutEnvelope)+1:end,:);
    CombinedPercussionsEnd = CombinedPercussionsEnd .* PercFadeoutEnvelope;
    CombinedPercussions = [CombinedPercussionsStart;CombinedPercussionsEnd];

    [Max, ~] = max(abs(CombinedPercussions(:)));
    CombinedPercussions = (CombinedPercussions/Max)';
    
    % combine instruments and percussion
    Song = (CombinedInstruments .* 0.4) + (CombinedPercussions' .* 0.6);   
    % add master reverb
    SongReverbL = FastConv(Song(:,1), IR_Reverb_Master(:,1));
    SongReverbR = FastConv(Song(:,2), IR_Reverb_Master(:,2));
    SongReverb = [SongReverbL, SongReverbR];
       
    % add reverberated signal to song
    lengthDry = length(Song);
    lengthWet = length(SongReverb);
    Song = ([Song', zeros(2, lengthWet-lengthDry)])';
    Song = Song + (SongReverb.*0.15);
    
    % normalize song
    [Max, ~] = max(abs(Song(:)));
    Song = Song/Max;

    audiowrite(strcat(songFileName, '.wav'), Song, Fs);
end
