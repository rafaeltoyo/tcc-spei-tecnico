using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using MySql.Data.MySqlClient;
using System.Windows.Forms;
using System.Drawing;

namespace PostNotes
{
    public class BDconnect
    {
    
        // ATRIBUTOS
        private static BDconnect instance;
        private static string login_session;
        private BDconnect() { }

        // SINGLETON
        public static BDconnect Instance
        {
            get 
            {
                if (instance == null)
                {
                    instance = new BDconnect();
                }
                return instance;
            }
        }

        // FUNÇÃO GET DO LOGIN
        public static string GetSessionLogin()
        {
            return login_session;
        }
        public static void DeletSessionLogin()
        {
            login_session = null;
        }

        // TENTATIVA DE CONEXÃO SQL
        private static MySqlConnection conexao;
        // FUNÇÃO DE CONEXÃO COM BANCO
        public static void ConnectInDB()
        {

            try
            {
                string source = "Server=mysql05-farm51.kinghost.net;" +
                    "Database=postnotes;" +
                    "User ID = postnotes;" +
                    "Pooling=false;" +
                    "Password =tecno123;";

                conexao = new MySqlConnection(source);
                conexao.Open();
            }
            catch
            {
                
            }
            
        }
        // FUNÇÃO LOGIN
        public static bool Login(string loginT,string passT)
        {

            try
            {
                MySqlCommand comando = conexao.CreateCommand();
                comando.CommandText = "SELECT * FROM user";
                MySqlDataReader dados = comando.ExecuteReader();
                while (dados.Read())
                {
                    string _login = (string)dados["login"];
                    string _pass = (string)dados["pass"];
                    if (loginT == _login && _pass == Encrypt.Hash.encrypt(passT))
                    {
                        login_session = _login;
                        dados.Close();
                        return true;
                    }
                }
                dados.Close();
                return false;
            }
            catch
            {
                return false;
            }
            
        }

        // FUNÇÃO QUERY DE NOTAS
        public static List<NotesInfo> GetNoteList(string type, int id_notes, int id_schedule)
        {
            try
            {
                MySqlCommand comando = conexao.CreateCommand();             
                
                comando.CommandText = "SELECT notes.* , unotes.alarm AS alarm FROM notes INNER JOIN unotes ON notes.id_notes = unotes.id_notes WHERE unotes.login = '"+login_session+"'";
                comando.CommandText += (id_notes != 0 ? " AND notes.id_notes =" + id_notes : "");
                comando.CommandText += (id_schedule != 0 ? " AND notes.id_schedule =" + id_schedule : "");
                comando.CommandText += " ORDER BY unotes.alarm";

                MySqlDataReader dados = comando.ExecuteReader();
                List<NotesInfo> note = new List<NotesInfo>();
                int i = 0;
                while (dados.Read())
                {
                    NotesInfo note_temp = new NotesInfo();
                    note_temp.SetValues((int)dados["id_notes"], (int)dados["id_schedule"], (string)dados["creator"], (string)dados["annotation"], (string)dados["alarm"], (string)dados["date"]);

                    note.Add(note_temp);
                    i++;
                }
                dados.Close();

                return note;
            }
            catch
            {
                return null;
            }
        }

        // FUNÇÃO DELETA ALARMES
        public static void RemoveNote(int id_note)
        {
            try
            {
                MySqlCommand comando = conexao.CreateCommand();

                comando.CommandText = "DELETE FROM unotes WHERE id_notes = "+id_note+" AND login LIKE '"+login_session+"'";

                MySqlDataReader dados = comando.ExecuteReader();
                dados.Close();
            }
            catch
            {
                
            }
        }

        // FUNÇÃO ATUALIZAR ALARME
        public static void ChangeAlarm(int id_note, string new_alarm)
        {
            try
            {
                MySqlCommand comando = conexao.CreateCommand();

                comando.CommandText = "UPDATE unotes SET alarm = '" + new_alarm + "' WHERE id_notes = " + id_note + " AND login LIKE '" + login_session + "' ";

                MySqlDataReader dados = comando.ExecuteReader();
                dados.Close();
            }
            catch
            {

            }
        }

        // FUNÇÃO DE CONVERTER DATA UTC - 00:00
        public static DateTime GetTimeZoneDate(DateTime data)
        {
            int timezone = 0;
            try
            {
                MySqlCommand comando = conexao.CreateCommand();

                comando.CommandText = "SELECT time_zone.utc FROM time_zone INNER JOIN user ON user.id_timezone = time_zone.id_timezone WHERE user.login LIKE '" + login_session + "' ";

                MySqlDataReader dados = comando.ExecuteReader();
                while (dados.Read())
                {
                    timezone = Int32.Parse(dados["utc"].ToString());
                }
                dados.Close();
            }
            catch
            {

            }

            DateTime newAlarm = data.AddHours(timezone);

            return newAlarm;
        }
        
    }

}
