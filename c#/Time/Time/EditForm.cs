using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static System.Windows.Forms.VisualStyles.VisualStyleElement;

namespace Time
{
    public partial class EditForm : Form
    {
        public string EditedText { get; private set; }

        public EditForm(string currentText)
        {
            InitializeComponent();
            edit_text.Text = currentText; // загрузка текущего текста в текстовое поле
        }

        private void buttonOk_Click(object sender, EventArgs e)
        {
            EditedText = edit_text.Text; // сохраняем текст для возврата
            this.DialogResult = DialogResult.OK; // указываем результат диалога
            this.Close(); // закрываем форму
        }

        private void buttonCancel_Click(object sender, EventArgs e)
        {
            this.DialogResult = DialogResult.Cancel; // указываем отмену диалога
            this.Close(); // закрываем форму
        }
    }

}
