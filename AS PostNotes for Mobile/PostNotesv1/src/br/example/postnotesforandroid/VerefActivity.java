package br.example.postnotesforandroid;

import android.support.v7.app.ActionBarActivity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;

public class VerefActivity extends ActionBarActivity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_veref);
		
		Intent intent = getIntent();
		
		if(intent != null) {
			Bundle params = intent.getExtras();
			if(params != null) {
				String login = params.getString("login");
				String senha = params.getString("senha");
				
				String answer = callServer("make-login",login,senha);
								
				if (login != null && senha != null && answer.equals("Logado com sucesso!")) {
					returnTrue(login);
				} else {
					returnFalse();
				}
			}
		}
	} // onCreate end;
	
	public void returnTrue(String login) {
		Intent intent = new Intent();
		intent.putExtra("login", login);
		intent.putExtra("user", "Sucesso");
		setResult(1, intent);
		finish();
	}
	public void returnFalse() {
		Intent intent = new Intent();
		intent.putExtra("msg", "Falha");
		setResult(2, intent);
		finish();
	}
	
	private String resposta;
	private String callServer(final String method, final String user, final String pass){
		new Thread(){
			public void run(){
				String answer = HttpConnection.sendHttpPost("http://www.postnotes.com.br/JSON/processo.php", method, user, pass);
				
				Log.i("Script", "ANSWER: "+answer);
				
				resposta = answer;
			}
		}.start();
		
		return resposta;
	}

}