classdef Instrument
    % A struct for storing instrument data
    properties
        Name
        Makam
        NoteDir
        NoteFiles
        Notes
        FadeInEnvelope
        FadeOutEnvelope
        NoteOffset
    end
    
    methods
        % c'tor
        function obj = Instrument(name, makam, noteDir)
            obj.NoteOffset = 1000;
            obj.Name = name;
            obj.Makam = makam;
            
            obj.NoteDir = dir(strcat(noteDir, '/*.wav'));
            obj.NoteFiles = cell(length(obj.NoteDir),1);
            obj.Notes = cell(length(obj.NoteDir),1);
            for i = 1:length(obj.NoteDir)
                obj.NoteFiles{i} = obj.NoteDir(i).name;
                [obj.Notes{i}, ~] = audioread(strcat(noteDir, '/', obj.NoteFiles{i}));
            end  
            obj.FadeOutEnvelope = 0:1/50:1;
            obj.FadeOutEnvelope = fliplr(obj.FadeOutEnvelope);
            obj.FadeOutEnvelope = obj.FadeOutEnvelope(2 : end);
            
            obj.FadeInEnvelope = 0:1/50:1;
        end
        
        function noteData = GetNoteData(obj, noteName, sampleCount)
            noteData = [];
            noteFilesCount = length(obj.NoteFiles);
            currFileName = '';
            noteName = strcat(noteName, '.wav');
            if strcmp(noteName, 'X.wav')
                noteData = zeros(sampleCount, 1);
            else
                for i = 1:noteFilesCount
                    currFileName = obj.NoteFiles{i};
                    % disp(strcat('currFileName:',currFileName,'---noteName:', noteName));
                    if strcmp(noteName, currFileName)
                        noteData = obj.Notes{i}; 
                        break
                    end
                end
            end
            
            if sampleCount < length(noteData)
                noteData = noteData(obj.NoteOffset : obj.NoteOffset+sampleCount);
            else
                % disp(strcat('veo: ', num2str(length(noteData)), '-'));
                noteData = ([noteData', zeros(1, sampleCount-length(noteData))])';
            end
            noteData = noteData';
            % fade in
            noteData = [noteData(1 : length(obj.FadeInEnvelope)) .* obj.FadeInEnvelope , noteData(length(obj.FadeInEnvelope)+1 : end)];
            % fade out
            noteData = [noteData(1 : end-length(obj.FadeOutEnvelope)) , noteData(end-length(obj.FadeOutEnvelope)+1 : end) .* obj.FadeOutEnvelope];
        end
        
    end
    
end

