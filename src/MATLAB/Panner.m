function [y] = Panner(sig, angle)
    % pans stereo signal between -45 and 45 degrees
    angle = angle*pi / 180; % convert to radians
    A = [cos(angle), sin(angle); -sin(angle), cos(angle)];
    y = A * sig;
end