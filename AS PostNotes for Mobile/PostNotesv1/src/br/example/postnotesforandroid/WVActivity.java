package br.example.postnotesforandroid;

import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.webkit.WebSettings;
import android.webkit.WebView;

public class WVActivity extends ActionBarActivity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_wv);
		
		WebView wv = (WebView) findViewById(R.id.webView1);
		
		WebSettings ws = wv.getSettings();
		ws.setJavaScriptEnabled(true);
		ws.setSupportZoom(false);
		
		wv.loadUrl("http://www.postnotes.esy.es/index.php");
	}

}
