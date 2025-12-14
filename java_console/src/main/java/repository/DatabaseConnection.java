package repository;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConnection {

    private static final String URL =
            "jdbc:postgresql://ep-solitary-cake-a4najv73-pooler.us-east-1.aws.neon.tech/brasil_burger_db"
                    + "?sslmode=require&channel_binding=require";

    private static final String USER = "neondb_owner";
    private static final String PASSWORD = "npg_Vy0MDeICAi8h";

    public static Connection getConnection() throws SQLException {
        try {
            // Chargement explicite du driver JDBC PostgreSQL
            Class.forName("org.postgresql.Driver");
        } catch (ClassNotFoundException e) {
            throw new SQLException("Driver PostgreSQL introuvable dans le classpath", e);
        }

        return DriverManager.getConnection(URL, USER, PASSWORD);
    }
}
