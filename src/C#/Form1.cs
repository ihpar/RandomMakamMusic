using System;
using System.Text;
using System.Windows.Forms;
using NAudio.Wave;
using System.IO;

namespace RadioRecorder
{
    public partial class FormRR : Form
    {
        public WaveIn WaveSource;
        public WaveFileWriter WaveFile;
        public StreamWriter StreamWriter;
        public StringBuilder Sb;
        public int Iterarot;


        public FormRR()
        {
            InitializeComponent();
        }

        private void btnStartRecording_Click(object sender, EventArgs e)
        {
            btnStartRecording.Enabled = false;
            btnStopRecording.Enabled = true;

            Iterarot = 0;

            Sb = new StringBuilder();
            StreamWriter = new StreamWriter(@"D:\Test\data.txt");

            WaveSource = new WaveIn {WaveFormat = new WaveFormat(44100, 1)};

            WaveSource.DataAvailable += waveSource_DataAvailable;
            WaveSource.RecordingStopped += waveSource_RecordingStopped;

            WaveFile = new WaveFileWriter(@"D:\Test\Test0001.wav", WaveSource.WaveFormat);

            WaveSource.StartRecording();
        }

        private void btnStopRecording_Click(object sender, EventArgs e)
        {
            btnStopRecording.Enabled = false;
            WaveSource.StopRecording();
            StreamWriter.Close();
            Sb.Clear();
            Iterarot = 0;
        }

        private void waveSource_DataAvailable(object sender, WaveInEventArgs e)
        {
            if (WaveFile == null) return;

            WaveFile.Write(e.Buffer, 0, e.BytesRecorded);
            if (Iterarot > 2)
            {
                foreach (byte b in e.Buffer)
                {
                    Sb.Append(b + Environment.NewLine);
                }
                StreamWriter.WriteLine(Sb.ToString());
                Sb.Clear();
            }
            WaveFile.Flush();
            Iterarot++;
        }

        private void waveSource_RecordingStopped(object sender, StoppedEventArgs e)
        {
            if (WaveSource != null)
            {
                WaveSource.Dispose();
                WaveSource = null;
            }

            if (WaveFile != null)
            {
                WaveFile.Dispose();
                WaveFile = null;
            }

            btnStartRecording.Enabled = true;
        }
    }
}
