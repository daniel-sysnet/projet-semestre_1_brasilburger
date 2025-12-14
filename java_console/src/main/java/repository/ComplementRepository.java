package repository;

import entity.Complement;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ComplementRepository {
    public void save(Complement complement) {
        String sql = "INSERT INTO Complement (nom, prix, image, description) VALUES (?, ?, ?, ?)";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS)) {
            stmt.setString(1, complement.getNom());
            stmt.setDouble(2, complement.getPrix());
            stmt.setString(3, complement.getImage());
            stmt.setString(4, complement.getDescription());
            stmt.executeUpdate();
            ResultSet rs = stmt.getGeneratedKeys();
            if (rs.next()) {
                complement.setId(rs.getInt(1));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void update(Complement complement) {
        String sql = "UPDATE Complement SET nom = ?, prix = ?, image = ?, description = ? WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, complement.getNom());
            stmt.setDouble(2, complement.getPrix());
            stmt.setString(3, complement.getImage());
            stmt.setString(4, complement.getDescription());
            stmt.setInt(5, complement.getId());
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void archive(int id) {
        String sql = "DELETE FROM Complement WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public List<Complement> findAll() {
        List<Complement> complements = new ArrayList<>();
        String sql = "SELECT * FROM Complement";
        try (Connection conn = DatabaseConnection.getConnection();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                Complement complement = new Complement(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"),
                        rs.getString("image"), rs.getString("description"));
                complements.add(complement);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return complements;
    }

    public Complement findById(int id) {
        String sql = "SELECT * FROM Complement WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
             PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                return new Complement(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"),
                        rs.getString("image"), rs.getString("description"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}
