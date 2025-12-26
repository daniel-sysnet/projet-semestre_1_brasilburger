package views;

import repository.DatabaseConnection;

public class Main {
    public static void main(String[] args) {
        DatabaseConnection.initDatabase();
        MenuPrincipal menu = new MenuPrincipal();
        menu.demarrer();
    }
}