package repository;

import entity.Burger;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class BurgerRepository {
    public void save(Burger burger) {
        String sql = "INSERT INTO Burger (nom, prix, image, description) VALUES (?, ?, ?, ?)";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            stmt.setString(1, burger.getNom());
            stmt.setDouble(2, burger.getPrix());
            stmt.setString(3, burger.getImage());
            stmt.setString(4, burger.getDescription());
            stmt.executeUpdate();
            ResultSet rs = stmt.getGeneratedKeys();
            if (rs.next()) {
                burger.setId(rs.getInt(1));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void update(Burger burger) {
        String sql = "UPDATE Burger SET nom = ?, prix = ?, image = ?, description = ? WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, burger.getNom());
            stmt.setDouble(2, burger.getPrix());
            stmt.setString(3, burger.getImage());
            stmt.setString(4, burger.getDescription());
            stmt.setInt(5, burger.getId());
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void archive(int id) {
        String sql = "DELETE FROM Burger WHERE id = ?";
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
        String sql = "SELECT * FROM Burger";
        try (Connection conn = DatabaseConnection.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                Burger burger = new Burger(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"),
                        rs.getString("image"), rs.getString("description"));
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
                        rs.getString("description"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}