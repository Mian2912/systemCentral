/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package controllers;

import java.util.List;
import javax.swing.table.DefaultTableModel;
import models.Employee;
import models.Speciality;
import services.SpecialityService;

/**
 *
 * @author migue
 */
public class SpecialityController {
    
    public DefaultTableModel loadTable(){
        String[] title = {"id", "No", "Especialidad"};
        String[] rows = new String[3];
        DefaultTableModel model = new DefaultTableModel(null, title);
        List<Speciality> specialitys = new SpecialityService().getSpecilitys();
        int i = 1;

        for (Speciality speciality: specialitys){
            rows[0] = String.valueOf(speciality.getId());
            rows[1] = String.valueOf(i++);
            rows[2] = speciality.getSpecialitys();
            model.addRow(rows);
        }
        return model;
    }
}