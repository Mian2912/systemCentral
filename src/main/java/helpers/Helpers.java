/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Interface.java to edit this template
 */
package helpers;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.Reader;
import java.net.HttpURLConnection;
import java.net.URL;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

/**
 *
 * @author migue
 */
public class Helpers {
    
    
    private static final String URL_API =  "http://api-rest-central.com/api/";
    
    public static HttpURLConnection getQueriesWithoutParameters(String urlQuery) throws IOException{
        
        URL url = new URL(URL_API+urlQuery);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("GET");
        
        return conn;
    }
    
    public static HttpURLConnection updateORCreate(String urlQuery, String methodHttp) throws IOException{
        
        URL url = new URL(URL_API+urlQuery);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod(methodHttp);
        conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
        conn.setDoOutput(true);
        return conn;
    }
    
    public static HttpURLConnection delete(String urlQuery) throws IOException{
        URL url = new URL(URL_API+urlQuery);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("DELETE");
        return conn;
    }

    public static JSONObject getResponseWithPostAndPut(HttpURLConnection conn, String parameters) throws IOException, ParseException{
    
        try (OutputStream output = conn.getOutputStream()) {
            output.write(parameters.getBytes());
            output.flush();
        }

        StringBuilder information = new StringBuilder();
        Reader in = new BufferedReader(new InputStreamReader(conn.getInputStream(), "UTF-8"));
        for (int c = in.read(); c != -1; c = in.read())
            information.append((char) c);

        Object data = new JSONParser().parse(information.toString());
        JSONObject jsonObject = (JSONObject) data;
        return jsonObject;
    }
    
    public static JSONObject getResponse(HttpURLConnection conn) throws IOException, ParseException{
        
        StringBuilder information = new StringBuilder();
        Reader in = new BufferedReader(new InputStreamReader(conn.getInputStream(), "UTF-8"));
        for (int c = in.read(); c != -1; c = in.read())
            information.append((char) c);

        Object data = new JSONParser().parse(information.toString());
        JSONObject jsonObject = (JSONObject) data;
        return jsonObject;
    }
    
}