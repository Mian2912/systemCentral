/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package controllers;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import javax.swing.table.DefaultTableModel;
import models.Cite;
import models.Employee;
import models.Status;
import services.CiteService;
import services.StatusService;

/**
 *
 * @author migue
 */
public class CiteController {
    
    public DefaultTableModel getCites(Employee employee){
        String[] title = {"id", "No", "Nombres", "Tipo De Documento", "Documento","Estado"};
        String[] rows = new String[6];
        DefaultTableModel model = new DefaultTableModel(null, title);
        int i = 1;
        List<Cite> cites = new CiteService().getCites(employee);

        for(Cite cite:cites){
            rows[0] = String.valueOf(cite.getId());
            rows[1] = String.valueOf(i++);
            rows[2] = cite.getName() + " " + cite.getLastname();
            rows[3] = cite.getType_document();
            rows[4] = cite.getDocument();
            Status status = new Status(cite.getId_status());
            status = new StatusService().getStatusById(status, employee);
            rows[5] = status.getStatus();
            model.addRow(rows);
        }
        
        return model;
    }

    public Cite confirmCite(Cite cite, Employee employee){
        Date date = new Date();
        SimpleDateFormat format = new SimpleDateFormat("yyyy/MM/dd");
        String dateNow = format.format(date);
        
        if(dateNow.equals(cite.getDate())){
            cite.setAlert("elija una fecha diferente");
            return cite;
        }

        cite = new CiteService().confirmCite(cite, employee);
        return cite;
    }
    
    public DefaultTableModel getCitesByParameters(String parameters, Employee employee){
        String[] title = {"id", "No", "Nombres", "Tipo De Documento", "Documento","Estado"};
        String[] rows = new String[6];
        DefaultTableModel model = new DefaultTableModel(null, title);
        int i = 1;
        List<Cite> cites = new CiteService().getCitesByParameters(parameters, employee);

        for(Cite cite:cites){
            rows[0] = String.valueOf(cite.getId());
            rows[1] = String.valueOf(i++);
            rows[2] = cite.getName() + " " + cite.getLastname();
            rows[3] = cite.getType_document();
            rows[4] = cite.getDocument();
            Status status = new Status(cite.getId_status());
            status = new StatusService().getStatusById(status, employee);
            rows[5] = status.getStatus();
            model.addRow(rows);
        }
        return model;
    }
    
    public Cite solicitedFiles(Cite cite, Employee employee){
        if(cite.getRequired_files().equalsIgnoreCase("ninguno")){          
            cite.setAlert("Seleccione el archivo requerido");
            return cite;
        }

        cite = new CiteService().solicitedFiles(cite, employee);
        return cite;
    }

}
