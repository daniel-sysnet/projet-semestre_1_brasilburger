package repository;

import entity.Burger;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class BurgerRepository {
    public void save(Burger burger) {
        String sql = "INSERT INTO Burger (id, nom, prix, image, description, actif) VALUES (?, ?, ?, ?, ?, ?)";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            // Calculer le prochain ID
            String maxIdSql = "SELECT COALESCE(MAX(id), 0) + 1 FROM Burger";
            try (PreparedStatement maxStmt = conn.prepareStatement(maxIdSql);
                    ResultSet rs = maxStmt.executeQuery()) {
                int nextId = 1;
                if (rs.next()) {
                    nextId = rs.getInt(1);
                }
                stmt.setInt(1, nextId);
                stmt.setString(2, burger.getNom());
                stmt.setDouble(3, burger.getPrix());
                stmt.setString(4, burger.getImage());
                stmt.setString(5, burger.getDescription());
                stmt.setBoolean(6, burger.isActif());
                stmt.executeUpdate();
                burger.setId(nextId);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void update(Burger burger) {
        String sql = "UPDATE Burger SET nom = ?, prix = ?, image = ?, description = ?, actif = ? WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, burger.getNom());
            stmt.setDouble(2, burger.getPrix());
            stmt.setString(3, burger.getImage());
            stmt.setString(4, burger.getDescription());
            stmt.setBoolean(5, burger.isActif());
            stmt.setInt(6, burger.getId());
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void archive(int id) {
        String sql = "UPDATE Burger SET actif = false WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public List<Burger> findAll() {
        List<Burger> burgers = new ArrayList<>();
        String sql = "SELECT * FROM Burger WHERE actif = true";
        try (Connection conn = DatabaseConnection.getConnection();
                Statement stmt = conn.createStatement();
                ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                Burger burger = new Burger(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"),
                        rs.getString("image"), rs.getString("description"), rs.getBoolean("actif"));
                burgers.add(burger);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return burgers;
    }

    public Burger findById(int id) {
        String sql = "SELECT * FROM Burger WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                return new Burger(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"), rs.getString("image"),
                        rs.getString("description"), rs.getBoolean("actif"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}