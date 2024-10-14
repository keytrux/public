using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Time
{
    public partial class Form1 : Form
    {
        private TimeSpan elapsedTime;
        private DateTime startTime;
        private bool running;
        public Form1()
        {
            InitializeComponent();
            elapsedTime = new TimeSpan();
            timer1.Interval = 1;
            timer1.Tick += timer1_Tick;
        }

        private void btn_calculate_Click(object sender, EventArgs e)
        {
            string hourInput = hour.Value.ToString();
            int hourInt = int.Parse(hourInput);

            string time = input.Text.ToString();
            int timeInt = 0;
            if (time.Length > 0)
            {
                timeInt = int.Parse(time);
            }

            float result = 0;
            result = ((hourInt * 60) + timeInt) / 60f;
            
            result_text.Text = result.ToString("0.0");

        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            UpdateTimeDisplay();
        }

        private void start_Click(object sender, EventArgs e)
        {
            if (running)
            {
                timer1.Stop();
                elapsedTime += DateTime.Now - startTime;
                start.Text = "Пуск";
            }
            else
            {
                startTime = DateTime.Now;
                timer1.Start();
                start.Text = "Стоп";
            }
            running = !running;
        }

        private void btn_reset_Click(object sender, EventArgs e)
        {
            timer1.Stop();
            running = false;
            elapsedTime = new TimeSpan(0);
            UpdateTimeDisplay();
            start.Text = "Старт";
        }

        private void UpdateTimeDisplay()
        {
            TimeSpan currentTimeSpan = elapsedTime;
            if(running)
            {
                currentTimeSpan += DateTime.Now - startTime;
            }
            labelTime.Text = string.Format("{0:hh\\:mm\\:ss}", currentTimeSpan);
        }

        private void btn_add1min_Click(object sender, EventArgs e)
        {
            Minutes(1);
        }

        private void btn_add5min_Click(object sender, EventArgs e)
        {
            Minutes(5);
        }

        private void btn_add10min_Click(object sender, EventArgs e)
        {
            Minutes(10);
        }

        private void Minutes(int minutes)
        {
            if(running)
            {
                timer1.Stop();
                elapsedTime += DateTime.Now - startTime;
                elapsedTime += TimeSpan.FromMinutes(minutes);
                startTime = DateTime.Now;
                timer1.Start();
            }
            else
            {
                elapsedTime += TimeSpan.FromMinutes(minutes);
            }
            UpdateTimeDisplay();
        }

        private void btn_del1min_Click(object sender, EventArgs e)
        {
            Minutes(-1);
        }

        private void btn_del5min_Click(object sender, EventArgs e)
        {
            Minutes(-5);
        }

        private void btn_del10min_Click(object sender, EventArgs e)
        {
            Minutes(-10);
        }

        private void btn_calculate_timer_Click(object sender, EventArgs e)
        {
            double totalHours = elapsedTime.TotalHours;
            log.Text = totalHours.ToString("F2");

            log_text.Items.Add(DateTime.Now.ToString("dd.MM.yyyy HH:mm:ss      ") + totalHours.ToString("F2") + "h");
        }

        private void add_btn_Click(object sender, EventArgs e)
        {
            Add add = new Add();
            add.Show();
        }
    }
}
