#include <Wire.h>

unsigned char freqH = 0;
unsigned char freqL = 0;

unsigned int freqB;
double freq = 0;
int potansVal = 1;


void setup()
{
  Wire.begin();
  freq = 98.0;
  adjustFrequency();
}

void loop()
{
  potansVal = analogRead(0);
  freq = ((double)potansVal * (108.0 - 87.5)) / 1024.0 + 87.5;
  freq = ((int)(freq * 10)) / 10.0;
  adjustFrequency();
}

void adjustFrequency()
{
  freqB = 4 * (freq * 1000000 + 225000) / 32768;
  freqH = freqB >> 8;
  freqL = freqB & 0XFF;
  delay(100);
  Wire.beginTransmission(0x60);
  Wire.write(freqH);
  Wire.write(freqL);
  Wire.write(0xB0);
  Wire.write(0x10);
  Wire.write((byte)0x00);
  Wire.endTransmission();
  delay(100);
}
