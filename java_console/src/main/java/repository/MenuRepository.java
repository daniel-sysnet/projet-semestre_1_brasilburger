package repository;

import entity.Menu;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class MenuRepository {
    public void save(Menu menu) {
        String sql = "INSERT INTO Menu (nom, prix, image, description) VALUES (?, ?, ?, ?)";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            stmt.setString(1, menu.getNom());
            stmt.setDouble(2, menu.getPrix());
            stmt.setString(3, menu.getImage());
            stmt.setString(4, menu.getDescription());
            stmt.executeUpdate();
            ResultSet rs = stmt.getGeneratedKeys();
            if (rs.next()) {
                menu.setId(rs.getInt(1));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void update(Menu menu) {
        String sql = "UPDATE Menu SET nom = ?, prix = ?, image = ?, description = ? WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, menu.getNom());
            stmt.setDouble(2, menu.getPrix());
            stmt.setString(3, menu.getImage());
            stmt.setString(4, menu.getDescription());
            stmt.setInt(5, menu.getId());
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void archive(int id) {
        String sql = "DELETE FROM Menu WHERE id = ?";
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
        String sql = "SELECT * FROM Menu";
        try (Connection conn = DatabaseConnection.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                Menu menu = new Menu(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"), rs.getString("image"),
                        rs.getString("description"));
                menus.add(menu);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return menus;
    }

    public Menu findById(int id) {
        String sql = "SELECT * FROM Menu WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                return new Menu(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"), rs.getString("image"),
                        rs.getString("description"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}