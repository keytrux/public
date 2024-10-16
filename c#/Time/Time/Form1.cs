﻿using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
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
        private Timer timer;
        private DateTime lastCheckedDate;
        public Form1()
        {
            InitializeComponent();
            elapsedTime = new TimeSpan();
            timer1.Interval = 1;
            timer1.Tick += timer1_Tick;
            time_day_label.Text = "За день " + DateTime.Now.Day.ToString() + "." + DateTime.Now.Month.ToString();

            lastCheckedDate = DateTime.Now; // Устанавливаем начальную дату
            timer2.Interval = 3600000;
            timer2.Tick += Timer_Tick;
            timer2.Start();
        }
        private void Timer_Tick(object sender, EventArgs e)
        {
            DateTime currentDate = DateTime.Now; // Получаем текущую дату

            // Проверяем, изменился ли день
            if (currentDate.Date != lastCheckedDate.Date)
            {
                lastCheckedDate = currentDate; // Обновляем сохраненную дату
                time_day_label.Text = "За день " + lastCheckedDate.Day.ToString() + "." + lastCheckedDate.Month.ToString(); // Обновляем метку
            }
        }

        public void AddLogEntry(string entry)
        {
            log_text.Items.Add(entry);
        }

        public void UpdateListBox(string entry)
        {
            // Добавляем новую запись в начало ListBox
            log_text.Items.Insert(0, entry);
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
            
            result_text.Text = result.ToString("0.00");

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
        }

        private void add_btn_Click(object sender, EventArgs e)
        {
            double totalHours = elapsedTime.TotalHours;
            log.Text = totalHours.ToString("F2");
            //log_text.Items.Add(DateTime.Now.ToString("dd.MM.yyyy HH:mm:ss      ") + totalHours.ToString("F2") + "h");

            double sum = 0;

            foreach (var item in time_day.Items)
            {
                if (double.TryParse(item.ToString(), out double number))
                {
                    sum += number; // добавляем к общей сумме
                }
            }
            sum += totalHours;
            time_day.Items.Clear();
            time_day.Items.Add(sum.ToString("F2")); // Добавляем " h" в конце суммы

            Class.Time = totalHours.ToString("F2").Replace(',', '.');



            Add add = new Add(this);
            add.Show();
        }

        private void log_text_MouseDoubleClick(object sender, MouseEventArgs e)
        {
            if (log_text.SelectedItem != null)
            {
                // Получаем выбранный элемент как строку
                string selectedItem = log_text.SelectedItem.ToString();

                // Предполагаем, что дата в формате "dd.MM.yyyy HH:mm:ss" - это первые 19 символов
                // Убираем дату, чтобы оставить только текст
                string textWithoutDate = selectedItem.Length > 19 ? selectedItem.Substring(19).Trim() : string.Empty;

                // Копируем текст без даты в буфер обмена
                Clipboard.SetText(textWithoutDate);
                MessageBox.Show("Текст скопирован в буфер обмена: " + textWithoutDate);
            }
            else
            {
                MessageBox.Show("Пожалуйста, выберите элемент из списка.");
            }
        }

        private void Form1_Load(object sender, EventArgs e)
        {

            time_day_label.Text = "За день " + DateTime.Now.Day.ToString() + "." + DateTime.Now.Month.ToString();

            double sum = 0;

            string filePath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "log.txt");

            // Проверяем, существует ли файл
            if (File.Exists(filePath))
            {
                // Читаем все строки из файла
                string[] lines = File.ReadAllLines(filePath);

                // Добавляем каждую строку в ListBox
                foreach (var line in lines)
                {
                    log_text.Items.Add(line); // Предполагается, что у вас есть listBox1 на форме
                    string date_text = line.Length >= 10 ? line.Substring(0, 10) : line;
                    string date_now = DateTime.Now.Day.ToString() + "." + DateTime.Now.Month.ToString() + "." + DateTime.Now.Year.ToString();
                    if (date_text == date_now)
                    {
                        if (line.Length >= 29) // Убедитесь, что строка длинная
                        {
                            string numberString = line.Substring(25, 4).Trim();  // Удаляем пробелы
                            if (double.TryParse(numberString, System.Globalization.NumberStyles.Any, System.Globalization.CultureInfo.InvariantCulture, out double number))
                            {
                                // Успешное преобразование
                                sum += number;
                            }
                        }
                    }

                    
                }
                time_day.Items.Clear();
                time_day.Items.Add(sum.ToString("F2")); // Добавляем " h" в конце суммы
            }
            else
            {
                MessageBox.Show("Файл log.txt не найден.");
            }
        }

        private void select_date_ValueChanged(object sender, EventArgs e)
        {
            log_text.Items.Clear();
            string select_date_value = select_date.Value.ToString().Substring(0, 10);
            //MessageBox.Show(select_date_value.Substring(0, 10));

            string filePath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "log.txt");
            double sum = 0;
            // Проверяем, существует ли файл
            if (File.Exists(filePath))
            {
                string[] lines = File.ReadAllLines(filePath);
                foreach (var line in lines)
                {
                    //MessageBox.Show(line);
                    string date_text = line.Length >= 10 ? line.Substring(0, 10) : line;
                    //MessageBox.Show(date_text);

                    if(date_text == select_date_value)
                    {
                        log_text.Items.Add(line);
                        time_day_label.Text = "За день " + select_date_value.Substring(0,5);

                        if (line.Length >= 29) // Убедитесь, что строка длинная
                        {
                            string numberString = line.Substring(25, 4).Trim();  // Удаляем пробелы
                            if (double.TryParse(numberString, System.Globalization.NumberStyles.Any, System.Globalization.CultureInfo.InvariantCulture, out double number))
                            {
                                // Успешное преобразование
                                sum += number;
                            }
                        }

                        
                    }
                }
                time_day.Items.Clear();
                time_day.Items.Add(sum.ToString("F2")); // Добавляем " h" в конце суммы
            }
        }
    }
}
