using System;
using System.IO;
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
    public partial class Add : Form
    {
        private Form1 mainForm; // ссылка на Form1
        public Add(Form1 form)
        {
            InitializeComponent();
            mainForm = form; // сохраняем ссылку на переданную форму
        }

        private void btn_add_Click(object sender, EventArgs e)
        {
            string logEntry = DateTime.Now.ToString("dd.MM.yyyy HH:mm:ss      ") + Class.Time + "h " + comment.Text;
            //mainForm.AddLogEntry(logEntry); // добавляем запись в log_text
            WriteLogToFile(logEntry);
            mainForm.UpdateListBox(logEntry);
            this.Hide();
        }

        private void comment_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Enter)
                btn_add_Click(sender, e);
        }

        private void WriteLogToFile(string logEntry)
        {
            string filePath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "log.txt");

            try
            {
                // Создаём список для хранения всех строк
                List<string> allLines = new List<string>();

                // Если файл существует, считываем все строки
                if (File.Exists(filePath))
                {
                    allLines = File.ReadAllLines(filePath).ToList();
                }

                // Добавляем новую запись в начало списка
                allLines.Insert(0, logEntry);

                // Записываем все строки обратно в файл
                File.WriteAllLines(filePath, allLines);
            }
            catch (Exception ex)
            {
                MessageBox.Show("Ошибка при записи в файл: " + ex.Message);
            }
        }


    }
}
