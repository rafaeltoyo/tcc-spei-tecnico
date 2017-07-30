using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace PostNotes
{
    public partial class MainScreen : Form
    {
        public MainScreen()
        {
            if (BDconnect.GetSessionLogin() != null)
            {
                InitializeComponent();
                UserName.Text = BDconnect.GetSessionLogin() + "";
            } 
            else if ( BDconnect.GetSessionLogin() == null )
            {
                Application.Exit();
            }
            
        }
        private List<NotesInfo> notes;
        private void Form1_Load(object sender, EventArgs e)
        {
            
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            if (!Alarm.have_alert)
            {
                notes = BDconnect.GetNoteList("", 0, 0);
                foreach (NotesInfo notes_info_temp in notes) 
                {
                    DateTime alarm_temp;
                    DateTime.TryParse(notes_info_temp.Alarm, out alarm_temp);
                    if (DateTime.Compare(alarm_temp, DateTime.Now.ToUniversalTime()) <= 0) 
                    {
                        Alarm aviso_temp = new Alarm();
                        aviso_temp.Dados_nota = notes_info_temp;
                        Alarm.have_alert = true;
                        this.WindowState = FormWindowState.Normal;
                        aviso_temp.ShowDialog();

                        carregado = false;
                        NotesList.Refresh();
                    }
                }
            }
            
        }

        private void toolStrip1_ItemClicked(object sender, ToolStripItemClickedEventArgs e)
        {

        }

        private void toolStrip1_ItemClicked_1(object sender, ToolStripItemClickedEventArgs e)
        {

        }

        private bool carregado = false;
        private void NotesList_Paint(object sender, PaintEventArgs e)
        {
            if (!carregado)
            {
                NotesList.Controls.Clear();
                notes = BDconnect.GetNoteList("", 0, 0);

                int i = 0;
                foreach (NotesInfo notes_info_temp in notes)
                {
                    Panel notes_body = new Panel();
                    notes_body.Size = new Size(NotesList.Width, 70);
                    notes_body.Location = new Point(0, 75 * i);
                    notes_body.BackColor = Color.FromArgb(210, 210, 55);
                    NotesList.Controls.Add(notes_body);


                    Label notes_temp = new Label();
                    notes_temp.Location = new Point(5, 5);
                    notes_temp.Text = notes_info_temp.Texto + "";
                    notes_temp.Size = new Size(NotesList.Width*2/5, 30);
                    notes_body.Controls.Add(notes_temp);

                    DateTime alarm, date;
                    DateTime.TryParse(notes_info_temp.Alarm, out alarm);
                    DateTime.TryParse(notes_info_temp.Date, out date);

                    alarm = BDconnect.GetTimeZoneDate(alarm);
                    date = BDconnect.GetTimeZoneDate(date);

                    Label alarm_txt = new Label();
                    alarm_txt.Location = new Point(NotesList.Width * 2 / 5, 5);
                    alarm_txt.Text = "Seu alarme: " + alarm;
                    alarm_txt.Size = new Size(NotesList.Width * 3 / 5, 30);
                    notes_body.Controls.Add(alarm_txt);

                    Label date_txt = new Label();
                    date_txt.Location = new Point(NotesList.Width * 2 / 5, 35);
                    date_txt.Text = "Data final:  " + date;
                    date_txt.Size = new Size(NotesList.Width * 3 / 5, 30);
                    notes_body.Controls.Add(date_txt);


                    i++;
                }
                carregado = true;
            }
            
        }

        private void UserName_Click(object sender, EventArgs e)
        {
            
        }

        private void ButtonBack_Click(object sender, EventArgs e)
        {      
            MessageBox.Show("Até mais " + BDconnect.GetSessionLogin());
            BDconnect.DeletSessionLogin();
            Application.Restart();
        }

        private void timer1_Tick_1(object sender, EventArgs e)
        {
            carregado = false;
            NotesList.Refresh();
        }
    }
}
