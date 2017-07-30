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
    public partial class Alarm : Form
    {
        public static bool have_alert;

        private NotesInfo dados_nota;
        public NotesInfo Dados_nota
        {
            get { return dados_nota; }
            set { dados_nota = value; }
        }

        private double limit_alarm;


        public Alarm()
        {
            InitializeComponent();
        }

        private void Alarm_Load(object sender, EventArgs e)
        {
            DateTime alarm, date;
            DateTime.TryParse(dados_nota.Alarm, out alarm);
            DateTime.TryParse(dados_nota.Date, out date);
            limit_alarm = date.Subtract(alarm).TotalMinutes;
            
            if ( DateTime.Compare(date, DateTime.Now.ToUniversalTime()) <= 0 ) {
                AlarmIncrement.Hide();
                label1.Hide();
                AdiarBtn.Hide();
            }

            AlarmName.Text = dados_nota.Texto;
            if (limit_alarm < 0) { MessageBox.Show("ERROR!"); }
            if (limit_alarm >= 5) { AlarmIncrement.Items.Add(new ComboBoxItem("5 minutos", "5")); }
            if (limit_alarm >= 15) { AlarmIncrement.Items.Add(new ComboBoxItem("15 minutos", "15")); }
            if (limit_alarm >= 30) { AlarmIncrement.Items.Add(new ComboBoxItem("30 minutos", "30")); }
            if (limit_alarm >= 60) { AlarmIncrement.Items.Add(new ComboBoxItem("1 hora", "60")); }
            if (limit_alarm >= 180) { AlarmIncrement.Items.Add(new ComboBoxItem("3 horas", "180")); }
            if (limit_alarm >= 1440) { AlarmIncrement.Items.Add(new ComboBoxItem("1 dia", "1440")); }
                        
        }
        public class ComboBoxItem
        {
            public string Value;
            public string Text;

            public ComboBoxItem(string text, string val)
            {
                Value = val;
                Text = text;
            }

            public override string ToString()
            {
                return Text;
            }
        }


        private void FecharJanela()
        {
            have_alert = false;
            this.Dispose();
        }
        
        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            
        }

        private void DoneBtn_Click(object sender, EventArgs e)
        {
            BDconnect.RemoveNote(dados_nota.Id_notes);
            FecharJanela();
        }

        private void AdiarBtn_Click(object sender, EventArgs e)
        {
            int incremento;
            try
            {
                ComboBoxItem typeItem = (ComboBoxItem)AlarmIncrement.SelectedItem;
                incremento = Int32.Parse(typeItem.Value.ToString());
            }
            catch
            {
                incremento = 0;
            }
            
            if (incremento != 0) 
            {
                DateTime oldAlarm;
                DateTime.TryParse(dados_nota.Alarm, out oldAlarm);
                DateTime newAlarm = oldAlarm.AddMinutes(incremento);
                string newSqlAlarm = newAlarm.ToString("yyyy-MM-dd HH:mm:ss");
                BDconnect.ChangeAlarm(dados_nota.Id_notes, newSqlAlarm);
            }
            else
            {
                BDconnect.RemoveNote(dados_nota.Id_notes);
            }

            FecharJanela();
        }
        protected override void OnFormClosing(FormClosingEventArgs e)
        {
            base.OnFormClosing(e);

            if (e.CloseReason == CloseReason.WindowsShutDown) return;
            if (have_alert) {
                BDconnect.RemoveNote(dados_nota.Id_notes);
                have_alert = false;
            }
            
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        
    }
}
