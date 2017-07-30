using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace PostNotes
{
    static class Program
    {
        /// <summary>
        /// The main entry point for the application.
        /// </summary>
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);
            Login logar = new Login();
            logar.ShowDialog();

            if (BDconnect.GetSessionLogin() != "" && BDconnect.GetSessionLogin() != null)
            {
                Application.Run(new MainScreen());
            }
        }
    }
}
