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
    public partial class Add : Form
    {
        public Add()
        {
            InitializeComponent();
        }

        private void btn_add_Click(object sender, EventArgs e)
        {
            double totalHours = elapsedTime.TotalHours;
            log.Text = totalHours.ToString("F2");

            log_text.Items.Add(DateTime.Now.ToString("dd.MM.yyyy HH:mm:ss      ") + totalHours.ToString("F2") + "h");
        }
    }
}
