namespace PostNotes
{
    partial class MainScreen
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(MainScreen));
            this.CurrentTime = new System.Windows.Forms.Timer(this.components);
            this.Menu = new System.Windows.Forms.ToolStrip();
            this.UserName = new System.Windows.Forms.ToolStripLabel();
            this.ButtonBack = new System.Windows.Forms.ToolStripButton();
            this.NotesList = new System.Windows.Forms.Panel();
            this.RefreshAlarms = new System.Windows.Forms.Timer(this.components);
            this.Menu.SuspendLayout();
            this.SuspendLayout();
            // 
            // CurrentTime
            // 
            this.CurrentTime.Enabled = true;
            this.CurrentTime.Interval = 1000;
            this.CurrentTime.Tick += new System.EventHandler(this.timer1_Tick);
            // 
            // Menu
            // 
            this.Menu.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.Menu.AutoSize = false;
            this.Menu.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(224)))), ((int)(((byte)(224)))), ((int)(((byte)(224)))));
            this.Menu.Dock = System.Windows.Forms.DockStyle.None;
            this.Menu.Font = new System.Drawing.Font("Tw Cen MT", 10F);
            this.Menu.GripStyle = System.Windows.Forms.ToolStripGripStyle.Hidden;
            this.Menu.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.UserName,
            this.ButtonBack});
            this.Menu.Location = new System.Drawing.Point(0, 0);
            this.Menu.Name = "Menu";
            this.Menu.Size = new System.Drawing.Size(485, 40);
            this.Menu.TabIndex = 3;
            this.Menu.Text = "toolStrip1";
            this.Menu.ItemClicked += new System.Windows.Forms.ToolStripItemClickedEventHandler(this.toolStrip1_ItemClicked_1);
            // 
            // UserName
            // 
            this.UserName.AutoSize = false;
            this.UserName.Name = "UserName";
            this.UserName.Size = new System.Drawing.Size(150, 22);
            this.UserName.Click += new System.EventHandler(this.UserName_Click);
            // 
            // ButtonBack
            // 
            this.ButtonBack.Alignment = System.Windows.Forms.ToolStripItemAlignment.Right;
            this.ButtonBack.BackColor = System.Drawing.Color.DarkGray;
            this.ButtonBack.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Text;
            this.ButtonBack.Image = ((System.Drawing.Image)(resources.GetObject("ButtonBack.Image")));
            this.ButtonBack.ImageTransparentColor = System.Drawing.Color.Magenta;
            this.ButtonBack.Name = "ButtonBack";
            this.ButtonBack.RightToLeft = System.Windows.Forms.RightToLeft.No;
            this.ButtonBack.Size = new System.Drawing.Size(49, 37);
            this.ButtonBack.Text = "Logout";
            this.ButtonBack.Click += new System.EventHandler(this.ButtonBack_Click);
            // 
            // NotesList
            // 
            this.NotesList.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
            | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.NotesList.AutoScroll = true;
            this.NotesList.Location = new System.Drawing.Point(12, 43);
            this.NotesList.Name = "NotesList";
            this.NotesList.Size = new System.Drawing.Size(460, 493);
            this.NotesList.TabIndex = 4;
            this.NotesList.Paint += new System.Windows.Forms.PaintEventHandler(this.NotesList_Paint);
            // 
            // RefreshAlarms
            // 
            this.RefreshAlarms.Enabled = true;
            this.RefreshAlarms.Interval = 60000;
            this.RefreshAlarms.Tick += new System.EventHandler(this.timer1_Tick_1);
            // 
            // MainScreen
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 19F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.Gainsboro;
            this.ClientSize = new System.Drawing.Size(484, 562);
            this.Controls.Add(this.NotesList);
            this.Controls.Add(this.Menu);
            this.Font = new System.Drawing.Font("Tw Cen MT", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.Margin = new System.Windows.Forms.Padding(4, 5, 4, 5);
            this.MaximizeBox = false;
            this.Name = "MainScreen";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "PostNotes";
            this.Load += new System.EventHandler(this.Form1_Load);
            this.Menu.ResumeLayout(false);
            this.Menu.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Timer CurrentTime;
        private System.Windows.Forms.ToolStrip Menu;
        private System.Windows.Forms.ToolStripLabel UserName;
        private System.Windows.Forms.ToolStripButton ButtonBack;
        private System.Windows.Forms.Panel NotesList;
        private System.Windows.Forms.Timer RefreshAlarms;
    }
}

