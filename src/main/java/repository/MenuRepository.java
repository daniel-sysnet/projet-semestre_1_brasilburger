package repository;

import entity.Menu;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class MenuRepository {
    public void save(Menu menu) {
        String sql = "INSERT INTO menu (id, nom, prix, image, description, actif) VALUES (?, ?, ?, ?, ?, ?)";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            // Calculer le prochain ID
            String maxIdSql = "SELECT COALESCE(MAX(id), 0) + 1 FROM menu";
            try (PreparedStatement maxStmt = conn.prepareStatement(maxIdSql);
                    ResultSet rs = maxStmt.executeQuery()) {
                int nextId = 1;
                if (rs.next()) {
                    nextId = rs.getInt(1);
                }
                stmt.setInt(1, nextId);
                stmt.setString(2, menu.getNom());
                stmt.setDouble(3, menu.getPrix());
                stmt.setString(4, menu.getImage());
                stmt.setString(5, menu.getDescription());
                stmt.setBoolean(6, menu.isActif());
                stmt.executeUpdate();
                menu.setId(nextId);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void update(Menu menu) {
        String sql = "UPDATE menu SET nom = ?, prix = ?, image = ?, description = ?, actif = ? WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, menu.getNom());
            stmt.setDouble(2, menu.getPrix());
            stmt.setString(3, menu.getImage());
            stmt.setString(4, menu.getDescription());
            stmt.setBoolean(5, menu.isActif());
            stmt.setInt(6, menu.getId());
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void archive(int id) {
        String sql = "UPDATE menu SET actif = false WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public List<Menu> findAll() {
        List<Menu> menus = new ArrayList<>();
        String sql = "SELECT * FROM menu WHERE actif = true";
        try (Connection conn = DatabaseConnection.getConnection();
                Statement stmt = conn.createStatement();
                ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                Menu menu = new Menu(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"), rs.getString("image"),
                        rs.getString("description"), rs.getBoolean("actif"));
                menus.add(menu);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return menus;
    }

    public Menu findById(int id) {
        String sql = "SELECT * FROM menu WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                return new Menu(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"), rs.getString("image"),
                        rs.getString("description"), rs.getBoolean("actif"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}