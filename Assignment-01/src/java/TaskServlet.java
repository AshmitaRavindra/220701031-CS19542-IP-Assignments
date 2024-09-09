/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/JSP_Servlet/Servlet.java to edit this template
 */

import java.io.IOException;
import java.io.PrintWriter;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;

import jakarta.servlet.annotation.WebServlet;
@WebServlet("/TaskServlet")


/**
 *
 * @author AFRIN
 */
public class TaskServlet extends HttpServlet {
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        String title = request.getParameter("title");
        String desc = request.getParameter("desc");
        String duedate = request.getParameter("duedate");

        PrintWriter out = response.getWriter();
        
        out.println("<li class='task-item'>");
        out.println("<input type='checkbox'>");
        out.println("<div>");
        out.println("<label for='title' class='title'>" + title + "</label>");
        out.println("<span class='desc'>" + desc + "</span>");
        out.println("<span class='duedate'>" + duedate + "</span>");
        out.println("</div>");
        out.println("<button onclick='return editTask(this)'>Edit</button>");
        out.println("<button onclick='return deleteTask(this)'>Delete</button>");
        out.println("</li>");
        
        out.close();
    }
}
