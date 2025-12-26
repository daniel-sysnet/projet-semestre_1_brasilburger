package repository;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class DatabaseConnection {

    private static final String URL = "jdbc:postgresql://ep-solitary-cake-a4najv73-pooler.us-east-1.aws.neon.tech/brasil_burger_db"
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

    public static void initDatabase() {
        try (Connection conn = getConnection();
                java.sql.Statement stmt = conn.createStatement()) {

            // Ajouter la colonne 'actif' si elle n'existe pas
            stmt.execute("ALTER TABLE burger ADD COLUMN IF NOT EXISTS actif BOOLEAN DEFAULT true");
            stmt.execute("ALTER TABLE menu ADD COLUMN IF NOT EXISTS actif BOOLEAN DEFAULT true");
            stmt.execute("ALTER TABLE complement ADD COLUMN IF NOT EXISTS actif BOOLEAN DEFAULT true");

            // Mettre à jour les enregistrements existants pour définir actif = true si NULL
            stmt.execute("UPDATE burger SET actif = true WHERE actif IS NULL");
            stmt.execute("UPDATE menu SET actif = true WHERE actif IS NULL");
            stmt.execute("UPDATE complement SET actif = true WHERE actif IS NULL");

            System.out.println("Base de données initialisée avec succès.");
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
