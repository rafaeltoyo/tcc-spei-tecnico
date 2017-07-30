namespace PostNotes
{
    partial class Alarm
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            this.label1 = new System.Windows.Forms.Label();
            this.AlarmName = new System.Windows.Forms.Label();
            this.AdiarBtn = new System.Windows.Forms.Button();
            this.DoneBtn = new System.Windows.Forms.Button();
            this.AlarmIncrement = new System.Windows.Forms.ComboBox();
            this.timer1 = new System.Windows.Forms.Timer(this.components);
            this.SuspendLayout();
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(248, 9);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(83, 22);
            this.label1.TabIndex = 1;
            this.label1.Text = "Adiar em:";
            this.label1.TextAlign = System.Drawing.ContentAlignment.MiddleCenter;
            this.label1.Click += new System.EventHandler(this.label1_Click);
            // 
            // AlarmName
            // 
            this.AlarmName.AutoSize = true;
            this.AlarmName.Location = new System.Drawing.Point(12, 9);
            this.AlarmName.Name = "AlarmName";
            this.AlarmName.Size = new System.Drawing.Size(115, 22);
            this.AlarmName.TabIndex = 2;
            this.AlarmName.Text = "Nome Alarme";
            // 
            // AdiarBtn
            // 
            this.AdiarBtn.Location = new System.Drawing.Point(358, 50);
            this.AdiarBtn.Name = "AdiarBtn";
            this.AdiarBtn.Size = new System.Drawing.Size(79, 34);
            this.AdiarBtn.TabIndex = 3;
            this.AdiarBtn.Text = "ADIAR";
            this.AdiarBtn.UseVisualStyleBackColor = true;
            this.AdiarBtn.Click += new System.EventHandler(this.AdiarBtn_Click);
            // 
            // DoneBtn
            // 
            this.DoneBtn.Location = new System.Drawing.Point(237, 50);
            this.DoneBtn.Name = "DoneBtn";
            this.DoneBtn.Size = new System.Drawing.Size(115, 34);
            this.DoneBtn.TabIndex = 4;
            this.DoneBtn.Text = "PRONTO";
            this.DoneBtn.UseVisualStyleBackColor = true;
            this.DoneBtn.Click += new System.EventHandler(this.DoneBtn_Click);
            // 
            // AlarmIncrement
            // 
            this.AlarmIncrement.FormattingEnabled = true;
            this.AlarmIncrement.Location = new System.Drawing.Point(337, 6);
            this.AlarmIncrement.Name = "AlarmIncrement";
            this.AlarmIncrement.Size = new System.Drawing.Size(121, 30);
            this.AlarmIncrement.TabIndex = 5;
            this.AlarmIncrement.SelectedIndexChanged += new System.EventHandler(this.comboBox1_SelectedIndexChanged);
            // 
            // timer1
            // 
            this.timer1.Tick += new System.EventHandler(this.timer1_Tick);
            // 
            // Alarm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(10F, 22F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(470, 101);
            this.Controls.Add(this.AlarmIncrement);
            this.Controls.Add(this.DoneBtn);
            this.Controls.Add(this.AdiarBtn);
            this.Controls.Add(this.AlarmName);
            this.Controls.Add(this.label1);
            this.Font = new System.Drawing.Font("Tw Cen MT", 14.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.Margin = new System.Windows.Forms.Padding(5, 6, 5, 6);
            this.MaximizeBox = false;
            this.MinimizeBox = false;
            this.Name = "Alarm";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterParent;
            this.Text = "Alarm";
            this.Load += new System.EventHandler(this.Alarm_Load);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label AlarmName;
        private System.Windows.Forms.Button AdiarBtn;
        private System.Windows.Forms.Button DoneBtn;
        private System.Windows.Forms.ComboBox AlarmIncrement;
        private System.Windows.Forms.Timer timer1;
    }
}