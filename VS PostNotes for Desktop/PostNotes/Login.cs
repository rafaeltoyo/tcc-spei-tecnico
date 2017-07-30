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
    public partial class Login : Form
    {
        private string username, password;

        public Login()
        {
            InitializeComponent();
            BDconnect.ConnectInDB();
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void ButtonLogin_Click(object sender, EventArgs e)
        {
            username = Username.Text;
            password = Password.Text;
            if (BDconnect.Login(username, password))
            {
                this.Dispose();
            }
            else
            {
                MessageBox.Show("Login/Senha incorretos!");
            }
        }

        private void Login_Load(object sender, EventArgs e)
        {

        }
    }
}
