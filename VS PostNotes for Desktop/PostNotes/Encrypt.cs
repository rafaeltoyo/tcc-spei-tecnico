using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Security.Cryptography;

namespace PostNotes
{
    class Encrypt
    {


        public class Hash
        {
            public Hash() { }

            public enum HashType : int
            {
                MD5,
                SHA1,
                SHA256,
                SHA512
            }

            public static string encrypt(string _password)
            {

                string first = Encrypt.Hash.GetHash(_password, Encrypt.Hash.HashType.MD5);
                string second = Encrypt.Hash.GetHash(first, Encrypt.Hash.HashType.SHA512);
                return second;

            }

            public static string GetHash(string text, HashType hashType)
            {
                string hashString;
                switch (hashType)
                {
                    case HashType.MD5:
                        hashString = GetMD5(text);
                        break;
                    case HashType.SHA1:
                        hashString = GetSHA1(text);
                        break;
                    case HashType.SHA256:
                        hashString = GetSHA256(text);
                        break;
                    case HashType.SHA512:
                        hashString = GetSHA512(text);
                        break;
                    default:
                        hashString = "Invalid Hash Type";
                        break;
                }
                return hashString;
            }

            public static bool CheckHash(string original, string hashString, HashType hashType)
            {
                string originalHash = GetHash(original, hashType);
                return (originalHash == hashString);
            }

            private static string GetMD5(string text)
            {
                byte[] hashValue;
                byte[] message = ASCIIEncoding.ASCII.GetBytes(text);

                MD5 hashString = new MD5CryptoServiceProvider();
                string hex = "";

                hashValue = hashString.ComputeHash(message);
                foreach (byte x in hashValue)
                {
                    hex += String.Format("{0:x2}", x);
                }
                return hex;
            }

            private static string GetSHA1(string text)
            {
                byte[] hashValue;
                byte[] message = ASCIIEncoding.ASCII.GetBytes(text);

                SHA1Managed hashString = new SHA1Managed();
                string hex = "";

                hashValue = hashString.ComputeHash(message);
                foreach (byte x in hashValue)
                {
                    hex += String.Format("{0:x2}", x);
                }
                return hex;
            }

            private static string GetSHA256(string text)
            {
                byte[] hashValue;
                byte[] message = ASCIIEncoding.ASCII.GetBytes(text);

                SHA256Managed hashString = new SHA256Managed();
                string hex = "";

                hashValue = hashString.ComputeHash(message);
                foreach (byte x in hashValue)
                {
                    hex += String.Format("{0:x2}", x);
                }
                return hex;
            }

            private static string GetSHA512(string text)
            {
                byte[] hashValue;
                byte[] message = ASCIIEncoding.ASCII.GetBytes(text);

                SHA512Managed hashString = new SHA512Managed();
                string hex = "";

                hashValue = hashString.ComputeHash(message);
                foreach (byte x in hashValue)
                {
                    hex += String.Format("{0:x2}", x);
                }
                return hex;
            }
        }


    }
}
