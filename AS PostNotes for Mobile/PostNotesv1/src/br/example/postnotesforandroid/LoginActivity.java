package br.example.postnotesforandroid;

import android.annotation.SuppressLint;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.BitmapFactory;
import android.media.Ringtone;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class LoginActivity extends ActionBarActivity {
	public static final int CONSTANTE_TELA_1 = 1;
	public static final String PREFS_NAME = "PtNtsCurrentUserLogin";
	public static String login = "";
	public static String pass = "";
	public static String msg = "";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		
		// Restore preferences
		SharedPreferences settings = getSharedPreferences(PREFS_NAME, 0);
		String login_temp = settings.getString("login", "");
		String pass_temp = settings.getString("pass", "");
		login = login_temp;
		pass = pass_temp;
		// End restore
		
		if (!login.equals("") && !pass.equals("")) {
			logarHttp("make-login", login, pass);
		}
		
		Intent intent = getIntent();
		if (intent != null) {
			Bundle params = intent.getExtras();
			if (params != null) {
				String ola = params.getString("login");
				if (ola != null) {
					Toast.makeText(this, "Deslogado", Toast.LENGTH_LONG).show();
				}
			}
		}
		

	}
	@Override
	protected void onResume() {
		super.onResume();
	}
	@Override
	protected void onStop() {
		super.onStop();
		
		if (!login.equals("") && !pass.equals("")) {
			// Saving first login
			SharedPreferences settings = getSharedPreferences(PREFS_NAME, 0);
			SharedPreferences.Editor editor = settings.edit();
			editor.putString("login", login);
			editor.putString("pass", pass);
			editor.commit();
			// End saving
		}
		
	}

	public void Login(View view) {
		EditText nEt = (EditText) findViewById(R.id.campoLogin);
        EditText sEt = (EditText) findViewById(R.id.campoSenha);
		logarHttp("make-login",nEt.getText().toString(), sEt.getText().toString());
	}
	
	@SuppressLint({ "NewApi", "ShowToast" })
	private void logarHttp(final String method, final String user, final String senha) {
		Thread t = new Thread( new Runnable(){
			public void run(){
				msg = HttpConnection.sendHttpPost("http://www.postnotes.com.br/JSON/processo.php", method, user, senha);
				Log.i("Script", "ANSWER: "+msg);
			}
		});
		
		t.start();
		try {
			t.join();
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		Toast.makeText(getBaseContext(), msg, Toast.LENGTH_SHORT).show();
		if (msg.equals("Logado com sucesso!")) {
			if (!login.equals("") && !pass.equals("")) {
				// Saving first login
				SharedPreferences settings = getSharedPreferences(PREFS_NAME, 0);
				SharedPreferences.Editor editor = settings.edit();
				editor.putString("login", login);
				editor.putString("pass", pass);
				editor.commit();
				// End saving
			}
			
			Intent intent = new Intent(LoginActivity.this ,NotesActivity.class);
        	
        	Bundle params = new Bundle();
    		params.putString("login", user);
    		intent.putExtras(params);
        	
    		startActivity(intent);
    		finish();
		}
		

	}
	
	public void abrirIndex(View view) {
		Uri uri = Uri.parse("http://www.postnotes.esy.es/register.php");
		Intent intent = new Intent(Intent.ACTION_VIEW, uri);
		startActivity(intent);
	}
	
	public void chamarBR (View view) {
		Button bt = (Button) view;
		
		Intent intent = new Intent(bt.getText().toString());
		
		sendBroadcast(intent);
	}
	
	public void gerarNotificacao (Context context, Intent intent, CharSequence ticker, CharSequence titulo, CharSequence descricao) {
		NotificationManager nm = (NotificationManager) context.getSystemService(context.NOTIFICATION_SERVICE);
		PendingIntent p = PendingIntent.getActivity(context, 0, intent, 0);
		
		
		NotificationCompat.Builder builder = new NotificationCompat.Builder(context);
		builder.setTicker(ticker);
		builder.setContentTitle(titulo);
		// Descrição simples
		builder.setContentText(descricao);
		builder.setSmallIcon(R.drawable.ic_launcher);
		builder.setLargeIcon(BitmapFactory.decodeResource(context.getResources(), R.drawable.ic_launcher));
		builder.setContentIntent(p);
		
		// Descrição composta
		/*
		NotificationCompat.InboxStyle style = new NotificationCompat.InboxStyle();
		
		String[] descs = new String[]{"Descrição 1","Descrição 2","Descrição 3","Descrição 4"};
		for(int i = 0; i < descs.length; i++) {
			style.addLine(descs[i]);
		}
		builder.setStyle(style);
		*/
		
		Notification n = builder.build();
		n.vibrate = new long[]{150, 300, 150, 600};
		n.flags = Notification.FLAG_AUTO_CANCEL;
		nm.notify(R.drawable.ic_launcher, n);
		
		try {
			Uri som = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
			Ringtone toque = RingtoneManager.getRingtone(context, som);
			toque.play();
		}
		catch(Exception e){}
	}
}
