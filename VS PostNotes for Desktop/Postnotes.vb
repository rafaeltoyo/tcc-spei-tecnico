Public Class Form1

    Dim alarm As Date

    Private Sub Label1_Click(sender As Object, e As EventArgs) Handles Label1.Click

    End Sub

    Private Sub MaskedTextBox1_MaskInputRejected(sender As Object, e As MaskInputRejectedEventArgs) Handles NewTime.MaskInputRejected
        
    End Sub

    Private Sub Timer1_Tick(sender As Object, e As EventArgs) Handles Timer1.Tick
        Relogio.Text = TimeOfDay
        If alarm = Relogio.Text Then
            Timer1.Enabled = False
            MessageBox.Show("Alarme ae ou")
        End If
    End Sub

    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load

    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        alarm = NewTime.Text
        Timer1.Enabled = True
    End Sub
End Class
