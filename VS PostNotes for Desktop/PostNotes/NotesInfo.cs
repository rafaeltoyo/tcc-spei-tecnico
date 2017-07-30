using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PostNotes
{
    public class NotesInfo
    {
        private int id_notes;
        private int id_schedule;
        private string creator;
        private string text;
        private string alarm;
        private string date;

        public void SetValues(int id_notes1, int id_schedule1, string creator1, string text1, string alarm1, string date1)
        {
            id_notes = id_notes1;
            id_schedule = id_schedule1;
            creator = creator1;
            byte[] bytes = Encoding.Default.GetBytes(text1);
            text = Encoding.UTF8.GetString(bytes);
            alarm = alarm1;
            date = date1;
        }


        public int Id_notes
        {
            get { return id_notes; }
            set { id_notes = value; }
        }        
        public int Id_schedule
        {
            get { return id_schedule; }
            set { id_schedule = value; }
        }        
        public string Creator
        {
            get { return creator; }
            set { creator = value; }
        }        
        public string Texto
        {
            get { return text; }
            set { text = value; }
        }        
        public string Alarm
        {
            get { return alarm; }
            set { alarm = value; }
        }        
        public string Date
        {
            get { return date; }
            set { date = value; }
        }        


    }
}
