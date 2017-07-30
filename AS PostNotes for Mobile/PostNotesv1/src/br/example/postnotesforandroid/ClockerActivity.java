package br.example.postnotesforandroid;

import java.util.Calendar;

import android.support.v7.app.ActionBarActivity;
import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;

public class ClockerActivity extends ActionBarActivity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_clocker);
		
		boolean alarmeAtivo = (PendingIntent.getBroadcast(this, 0, new Intent("ALARME_DISPARADO"), PendingIntent.FLAG_NO_CREATE) == null);
		
		if (alarmeAtivo) {
			Log.i("Script", "Novo alarme");
			
			Intent intent = new Intent("ALARME_DISPARADO");
			PendingIntent p = PendingIntent.getBroadcast(this, 0, intent, 0);
			
			Calendar c = Calendar.getInstance();
			c.setTimeInMillis(System.currentTimeMillis());
			c.add(Calendar.SECOND , 3);
			
			AlarmManager alarme = (AlarmManager) getSystemService(ALARM_SERVICE);
			alarme.setRepeating(AlarmManager.RTC_WAKEUP , c.getTimeInMillis(), 30000, p);
		} else {
			Log.i("Script", "Alarme já ativo");
		}
	}
}
