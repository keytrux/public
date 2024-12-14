namespace Time
{
    partial class Form1
    {
        /// <summary>
        /// Обязательная переменная конструктора.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Освободить все используемые ресурсы.
        /// </summary>
        /// <param name="disposing">истинно, если управляемый ресурс должен быть удален; иначе ложно.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Код, автоматически созданный конструктором форм Windows

        /// <summary>
        /// Требуемый метод для поддержки конструктора — не изменяйте 
        /// содержимое этого метода с помощью редактора кода.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Form1));
            this.WorkArea = new System.Windows.Forms.TabControl();
            this.tabPage1 = new System.Windows.Forms.TabPage();
            this.add_btn = new System.Windows.Forms.Button();
            this.log = new System.Windows.Forms.Label();
            this.btn_calculate_timer = new System.Windows.Forms.Button();
            this.btn_del10min = new System.Windows.Forms.Button();
            this.btn_del5min = new System.Windows.Forms.Button();
            this.btn_del1min = new System.Windows.Forms.Button();
            this.btn_add10min = new System.Windows.Forms.Button();
            this.btn_add5min = new System.Windows.Forms.Button();
            this.btn_add1min = new System.Windows.Forms.Button();
            this.btn_reset = new System.Windows.Forms.Button();
            this.labelTime = new System.Windows.Forms.Label();
            this.start = new System.Windows.Forms.Button();
            this.tabPage2 = new System.Windows.Forms.TabPage();
            this.label1 = new System.Windows.Forms.Label();
            this.hour = new System.Windows.Forms.NumericUpDown();
            this.result_text = new System.Windows.Forms.Label();
            this.btn_calculate = new System.Windows.Forms.Button();
            this.input = new System.Windows.Forms.TextBox();
            this.tabPage3 = new System.Windows.Forms.TabPage();
            this.btn_standup = new System.Windows.Forms.Button();
            this.select_date = new System.Windows.Forms.DateTimePicker();
            this.time_day_label = new System.Windows.Forms.Label();
            this.time_day = new System.Windows.Forms.ListBox();
            this.log_text = new System.Windows.Forms.ListBox();
            this.timer1 = new System.Windows.Forms.Timer(this.components);
            this.timer2 = new System.Windows.Forms.Timer(this.components);
            this.contextMenuStrip1 = new System.Windows.Forms.ContextMenuStrip(this.components);
            this.Menu_edit = new System.Windows.Forms.ToolStripMenuItem();
            this.Menu_delete = new System.Windows.Forms.ToolStripMenuItem();
            this.WorkArea.SuspendLayout();
            this.tabPage1.SuspendLayout();
            this.tabPage2.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.hour)).BeginInit();
            this.tabPage3.SuspendLayout();
            this.contextMenuStrip1.SuspendLayout();
            this.SuspendLayout();
            // 
            // WorkArea
            // 
            this.WorkArea.Controls.Add(this.tabPage1);
            this.WorkArea.Controls.Add(this.tabPage2);
            this.WorkArea.Controls.Add(this.tabPage3);
            this.WorkArea.Location = new System.Drawing.Point(2, 4);
            this.WorkArea.Name = "WorkArea";
            this.WorkArea.SelectedIndex = 0;
            this.WorkArea.Size = new System.Drawing.Size(355, 306);
            this.WorkArea.TabIndex = 0;
            // 
            // tabPage1
            // 
            this.tabPage1.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage1.Controls.Add(this.add_btn);
            this.tabPage1.Controls.Add(this.log);
            this.tabPage1.Controls.Add(this.btn_calculate_timer);
            this.tabPage1.Controls.Add(this.btn_del10min);
            this.tabPage1.Controls.Add(this.btn_del5min);
            this.tabPage1.Controls.Add(this.btn_del1min);
            this.tabPage1.Controls.Add(this.btn_add10min);
            this.tabPage1.Controls.Add(this.btn_add5min);
            this.tabPage1.Controls.Add(this.btn_add1min);
            this.tabPage1.Controls.Add(this.btn_reset);
            this.tabPage1.Controls.Add(this.labelTime);
            this.tabPage1.Controls.Add(this.start);
            this.tabPage1.ForeColor = System.Drawing.SystemColors.ControlText;
            this.tabPage1.Location = new System.Drawing.Point(4, 22);
            this.tabPage1.Name = "tabPage1";
            this.tabPage1.Padding = new System.Windows.Forms.Padding(3);
            this.tabPage1.Size = new System.Drawing.Size(347, 280);
            this.tabPage1.TabIndex = 0;
            this.tabPage1.Text = "Секундомер";
            // 
            // add_btn
            // 
            this.add_btn.BackColor = System.Drawing.Color.WhiteSmoke;
            this.add_btn.Cursor = System.Windows.Forms.Cursors.Hand;
            this.add_btn.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.add_btn.Location = new System.Drawing.Point(256, 221);
            this.add_btn.Name = "add_btn";
            this.add_btn.Size = new System.Drawing.Size(75, 36);
            this.add_btn.TabIndex = 11;
            this.add_btn.Text = "Добавить";
            this.add_btn.UseVisualStyleBackColor = false;
            this.add_btn.Click += new System.EventHandler(this.add_btn_Click);
            // 
            // log
            // 
            this.log.AutoSize = true;
            this.log.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.log.Location = new System.Drawing.Point(270, 198);
            this.log.Name = "log";
            this.log.Size = new System.Drawing.Size(40, 20);
            this.log.TabIndex = 10;
            this.log.Text = "0.00";
            // 
            // btn_calculate_timer
            // 
            this.btn_calculate_timer.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_calculate_timer.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_calculate_timer.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_calculate_timer.Location = new System.Drawing.Point(256, 130);
            this.btn_calculate_timer.Name = "btn_calculate_timer";
            this.btn_calculate_timer.Size = new System.Drawing.Size(75, 36);
            this.btn_calculate_timer.TabIndex = 9;
            this.btn_calculate_timer.Text = "Посчитать";
            this.btn_calculate_timer.UseVisualStyleBackColor = false;
            this.btn_calculate_timer.Click += new System.EventHandler(this.btn_calculate_timer_Click);
            // 
            // btn_del10min
            // 
            this.btn_del10min.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_del10min.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_del10min.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_del10min.Location = new System.Drawing.Point(161, 234);
            this.btn_del10min.Name = "btn_del10min";
            this.btn_del10min.Size = new System.Drawing.Size(66, 23);
            this.btn_del10min.TabIndex = 8;
            this.btn_del10min.Text = "-10 мин";
            this.btn_del10min.UseVisualStyleBackColor = false;
            this.btn_del10min.Click += new System.EventHandler(this.btn_del10min_Click);
            // 
            // btn_del5min
            // 
            this.btn_del5min.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_del5min.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_del5min.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_del5min.Location = new System.Drawing.Point(89, 234);
            this.btn_del5min.Name = "btn_del5min";
            this.btn_del5min.Size = new System.Drawing.Size(66, 23);
            this.btn_del5min.TabIndex = 7;
            this.btn_del5min.Text = "-5 мин";
            this.btn_del5min.UseVisualStyleBackColor = false;
            this.btn_del5min.Click += new System.EventHandler(this.btn_del5min_Click);
            // 
            // btn_del1min
            // 
            this.btn_del1min.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_del1min.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_del1min.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_del1min.Location = new System.Drawing.Point(17, 234);
            this.btn_del1min.Name = "btn_del1min";
            this.btn_del1min.Size = new System.Drawing.Size(66, 23);
            this.btn_del1min.TabIndex = 6;
            this.btn_del1min.Text = "-1 мин";
            this.btn_del1min.UseVisualStyleBackColor = false;
            this.btn_del1min.Click += new System.EventHandler(this.btn_del1min_Click);
            // 
            // btn_add10min
            // 
            this.btn_add10min.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_add10min.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_add10min.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_add10min.Location = new System.Drawing.Point(161, 195);
            this.btn_add10min.Name = "btn_add10min";
            this.btn_add10min.Size = new System.Drawing.Size(66, 23);
            this.btn_add10min.TabIndex = 5;
            this.btn_add10min.Text = "+10 мин";
            this.btn_add10min.UseVisualStyleBackColor = false;
            this.btn_add10min.Click += new System.EventHandler(this.btn_add10min_Click);
            // 
            // btn_add5min
            // 
            this.btn_add5min.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_add5min.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_add5min.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_add5min.Location = new System.Drawing.Point(89, 195);
            this.btn_add5min.Name = "btn_add5min";
            this.btn_add5min.Size = new System.Drawing.Size(66, 23);
            this.btn_add5min.TabIndex = 4;
            this.btn_add5min.Text = "+5 мин";
            this.btn_add5min.UseVisualStyleBackColor = false;
            this.btn_add5min.Click += new System.EventHandler(this.btn_add5min_Click);
            // 
            // btn_add1min
            // 
            this.btn_add1min.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_add1min.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_add1min.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_add1min.Location = new System.Drawing.Point(17, 195);
            this.btn_add1min.Name = "btn_add1min";
            this.btn_add1min.Size = new System.Drawing.Size(66, 23);
            this.btn_add1min.TabIndex = 3;
            this.btn_add1min.Text = "+1 мин";
            this.btn_add1min.UseVisualStyleBackColor = false;
            this.btn_add1min.Click += new System.EventHandler(this.btn_add1min_Click);
            // 
            // btn_reset
            // 
            this.btn_reset.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_reset.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_reset.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_reset.Location = new System.Drawing.Point(128, 130);
            this.btn_reset.Name = "btn_reset";
            this.btn_reset.Size = new System.Drawing.Size(99, 36);
            this.btn_reset.TabIndex = 2;
            this.btn_reset.Text = "Сбросить";
            this.btn_reset.UseVisualStyleBackColor = false;
            this.btn_reset.Click += new System.EventHandler(this.btn_reset_Click);
            // 
            // labelTime
            // 
            this.labelTime.AutoSize = true;
            this.labelTime.BackColor = System.Drawing.Color.Gainsboro;
            this.labelTime.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.labelTime.Font = new System.Drawing.Font("Myanmar Text", 15.75F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.labelTime.ForeColor = System.Drawing.Color.FromArgb(((int)(((byte)(64)))), ((int)(((byte)(64)))), ((int)(((byte)(64)))));
            this.labelTime.Location = new System.Drawing.Point(124, 45);
            this.labelTime.Name = "labelTime";
            this.labelTime.Size = new System.Drawing.Size(103, 39);
            this.labelTime.TabIndex = 1;
            this.labelTime.Text = "00:00:00";
            // 
            // start
            // 
            this.start.BackColor = System.Drawing.Color.WhiteSmoke;
            this.start.Cursor = System.Windows.Forms.Cursors.Hand;
            this.start.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.start.Location = new System.Drawing.Point(17, 130);
            this.start.Name = "start";
            this.start.Size = new System.Drawing.Size(99, 36);
            this.start.TabIndex = 0;
            this.start.Text = "Старт";
            this.start.UseVisualStyleBackColor = false;
            this.start.Click += new System.EventHandler(this.start_Click);
            // 
            // tabPage2
            // 
            this.tabPage2.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage2.Controls.Add(this.label1);
            this.tabPage2.Controls.Add(this.hour);
            this.tabPage2.Controls.Add(this.result_text);
            this.tabPage2.Controls.Add(this.btn_calculate);
            this.tabPage2.Controls.Add(this.input);
            this.tabPage2.Location = new System.Drawing.Point(4, 22);
            this.tabPage2.Name = "tabPage2";
            this.tabPage2.Padding = new System.Windows.Forms.Padding(3);
            this.tabPage2.Size = new System.Drawing.Size(347, 280);
            this.tabPage2.TabIndex = 1;
            this.tabPage2.Text = "Калькулятор";
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(159, 79);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(13, 13);
            this.label1.TabIndex = 4;
            this.label1.Text = "h";
            // 
            // hour
            // 
            this.hour.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
            this.hour.Location = new System.Drawing.Point(99, 72);
            this.hour.Name = "hour";
            this.hour.Size = new System.Drawing.Size(54, 26);
            this.hour.TabIndex = 3;
            // 
            // result_text
            // 
            this.result_text.AutoSize = true;
            this.result_text.Font = new System.Drawing.Font("Microsoft Sans Serif", 14.25F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
            this.result_text.Location = new System.Drawing.Point(140, 150);
            this.result_text.Name = "result_text";
            this.result_text.Size = new System.Drawing.Size(49, 24);
            this.result_text.TabIndex = 2;
            this.result_text.Text = "0.00";
            // 
            // btn_calculate
            // 
            this.btn_calculate.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_calculate.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_calculate.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_calculate.Location = new System.Drawing.Point(111, 109);
            this.btn_calculate.Name = "btn_calculate";
            this.btn_calculate.Size = new System.Drawing.Size(107, 38);
            this.btn_calculate.TabIndex = 1;
            this.btn_calculate.Text = "Рассчитать";
            this.btn_calculate.UseVisualStyleBackColor = false;
            this.btn_calculate.Click += new System.EventHandler(this.btn_calculate_Click);
            // 
            // input
            // 
            this.input.Font = new System.Drawing.Font("Microsoft Sans Serif", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
            this.input.Location = new System.Drawing.Point(178, 70);
            this.input.Name = "input";
            this.input.Size = new System.Drawing.Size(53, 26);
            this.input.TabIndex = 0;
            // 
            // tabPage3
            // 
            this.tabPage3.BackColor = System.Drawing.SystemColors.Control;
            this.tabPage3.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.tabPage3.Controls.Add(this.btn_standup);
            this.tabPage3.Controls.Add(this.select_date);
            this.tabPage3.Controls.Add(this.time_day_label);
            this.tabPage3.Controls.Add(this.time_day);
            this.tabPage3.Controls.Add(this.log_text);
            this.tabPage3.Location = new System.Drawing.Point(4, 22);
            this.tabPage3.Name = "tabPage3";
            this.tabPage3.Size = new System.Drawing.Size(347, 280);
            this.tabPage3.TabIndex = 2;
            this.tabPage3.Text = "Логи";
            // 
            // btn_standup
            // 
            this.btn_standup.BackColor = System.Drawing.Color.WhiteSmoke;
            this.btn_standup.Cursor = System.Windows.Forms.Cursors.Hand;
            this.btn_standup.FlatStyle = System.Windows.Forms.FlatStyle.Popup;
            this.btn_standup.Location = new System.Drawing.Point(5, 236);
            this.btn_standup.Name = "btn_standup";
            this.btn_standup.Size = new System.Drawing.Size(99, 36);
            this.btn_standup.TabIndex = 4;
            this.btn_standup.Text = "Стэндап";
            this.btn_standup.UseVisualStyleBackColor = false;
            this.btn_standup.Click += new System.EventHandler(this.btn_standup_Click);
            // 
            // select_date
            // 
            this.select_date.Location = new System.Drawing.Point(3, 3);
            this.select_date.Name = "select_date";
            this.select_date.Size = new System.Drawing.Size(124, 20);
            this.select_date.TabIndex = 3;
            this.select_date.ValueChanged += new System.EventHandler(this.select_date_ValueChanged);
            // 
            // time_day_label
            // 
            this.time_day_label.AutoSize = true;
            this.time_day_label.Font = new System.Drawing.Font("Microsoft Sans Serif", 8.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
            this.time_day_label.Location = new System.Drawing.Point(251, 237);
            this.time_day_label.Name = "time_day_label";
            this.time_day_label.Size = new System.Drawing.Size(47, 13);
            this.time_day_label.TabIndex = 2;
            this.time_day_label.Text = "За день";
            // 
            // time_day
            // 
            this.time_day.Font = new System.Drawing.Font("Microsoft Sans Serif", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
            this.time_day.FormattingEnabled = true;
            this.time_day.ItemHeight = 16;
            this.time_day.Location = new System.Drawing.Point(254, 254);
            this.time_day.Name = "time_day";
            this.time_day.Size = new System.Drawing.Size(73, 20);
            this.time_day.TabIndex = 1;
            // 
            // log_text
            // 
            this.log_text.Cursor = System.Windows.Forms.Cursors.Hand;
            this.log_text.Font = new System.Drawing.Font("Microsoft Sans Serif", 9.75F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(204)));
            this.log_text.FormattingEnabled = true;
            this.log_text.HorizontalScrollbar = true;
            this.log_text.ItemHeight = 16;
            this.log_text.Location = new System.Drawing.Point(3, 29);
            this.log_text.Name = "log_text";
            this.log_text.RightToLeft = System.Windows.Forms.RightToLeft.No;
            this.log_text.Size = new System.Drawing.Size(337, 180);
            this.log_text.TabIndex = 0;
            this.log_text.MouseDoubleClick += new System.Windows.Forms.MouseEventHandler(this.log_text_MouseDoubleClick);
            // 
            // timer1
            // 
            this.timer1.Tick += new System.EventHandler(this.timer1_Tick);
            // 
            // contextMenuStrip1
            // 
            this.contextMenuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.Menu_edit,
            this.Menu_delete});
            this.contextMenuStrip1.Name = "contextMenuStrip1";
            this.contextMenuStrip1.RenderMode = System.Windows.Forms.ToolStripRenderMode.Professional;
            this.contextMenuStrip1.Size = new System.Drawing.Size(155, 48);
            // 
            // Menu_edit
            // 
            this.Menu_edit.Name = "Menu_edit";
            this.Menu_edit.Size = new System.Drawing.Size(154, 22);
            this.Menu_edit.Text = "Редактировать";
            this.Menu_edit.Click += new System.EventHandler(this.Menu_edit_Click);
            // 
            // Menu_delete
            // 
            this.Menu_delete.Name = "Menu_delete";
            this.Menu_delete.Size = new System.Drawing.Size(154, 22);
            this.Menu_delete.Text = "Удалить";
            this.Menu_delete.Click += new System.EventHandler(this.Menu_delete_Click);
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.SystemColors.Control;
            this.ClientSize = new System.Drawing.Size(358, 311);
            this.Controls.Add(this.WorkArea);
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.Name = "Form1";
            this.Text = "Time";
            this.Load += new System.EventHandler(this.Form1_Load);
            this.WorkArea.ResumeLayout(false);
            this.tabPage1.ResumeLayout(false);
            this.tabPage1.PerformLayout();
            this.tabPage2.ResumeLayout(false);
            this.tabPage2.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.hour)).EndInit();
            this.tabPage3.ResumeLayout(false);
            this.tabPage3.PerformLayout();
            this.contextMenuStrip1.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.TabControl WorkArea;
        private System.Windows.Forms.TabPage tabPage1;
        private System.Windows.Forms.TabPage tabPage2;
        private System.Windows.Forms.Button btn_calculate;
        private System.Windows.Forms.TextBox input;
        private System.Windows.Forms.Label result_text;
        private System.Windows.Forms.Timer timer1;
        private System.Windows.Forms.NumericUpDown hour;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Button start;
        private System.Windows.Forms.Label labelTime;
        private System.Windows.Forms.Button btn_reset;
        private System.Windows.Forms.Button btn_add10min;
        private System.Windows.Forms.Button btn_add5min;
        private System.Windows.Forms.Button btn_add1min;
        private System.Windows.Forms.Button btn_del10min;
        private System.Windows.Forms.Button btn_del5min;
        private System.Windows.Forms.Button btn_del1min;
        private System.Windows.Forms.Button btn_calculate_timer;
        private System.Windows.Forms.Label log;
        private System.Windows.Forms.TabPage tabPage3;
        private System.Windows.Forms.Button add_btn;
        private System.Windows.Forms.Label time_day_label;
        private System.Windows.Forms.ListBox time_day;
        public System.Windows.Forms.ListBox log_text;
        private System.Windows.Forms.DateTimePicker select_date;
        private System.Windows.Forms.Timer timer2;
        private System.Windows.Forms.ContextMenuStrip contextMenuStrip1;
        private System.Windows.Forms.ToolStripMenuItem Menu_edit;
        private System.Windows.Forms.ToolStripMenuItem Menu_delete;
        private System.Windows.Forms.Button btn_standup;
    }
}

