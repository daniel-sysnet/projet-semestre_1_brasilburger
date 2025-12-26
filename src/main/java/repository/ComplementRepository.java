package repository;

import entity.Complement;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class ComplementRepository {
    public void save(Complement complement) {
        String sql = "INSERT INTO complement (id, nom, prix, image, description, actif) VALUES (?, ?, ?, ?, ?, ?)";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            // Calculer le prochain ID
            String maxIdSql = "SELECT COALESCE(MAX(id), 0) + 1 FROM complement";
            try (PreparedStatement maxStmt = conn.prepareStatement(maxIdSql);
                    ResultSet rs = maxStmt.executeQuery()) {
                int nextId = 1;
                if (rs.next()) {
                    nextId = rs.getInt(1);
                }
                stmt.setInt(1, nextId);
                stmt.setString(2, complement.getNom());
                stmt.setDouble(3, complement.getPrix());
                stmt.setString(4, complement.getImage());
                stmt.setString(5, complement.getDescription());
                stmt.setBoolean(6, complement.isActif());
                stmt.executeUpdate();
                complement.setId(nextId);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void update(Complement complement) {
        String sql = "UPDATE complement SET nom = ?, prix = ?, image = ?, description = ?, actif = ? WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setString(1, complement.getNom());
            stmt.setDouble(2, complement.getPrix());
            stmt.setString(3, complement.getImage());
            stmt.setString(4, complement.getDescription());
            stmt.setBoolean(5, complement.isActif());
            stmt.setInt(6, complement.getId());
            stmt.executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void archive(int id) {
        String sql = "UPDATE complement SET actif = false WHERE id = ?";
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
        String sql = "SELECT * FROM complement WHERE actif = true";
        try (Connection conn = DatabaseConnection.getConnection();
                Statement stmt = conn.createStatement();
                ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                Complement complement = new Complement(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"),
                        rs.getString("image"), rs.getString("description"), rs.getBoolean("actif"));
                complements.add(complement);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return complements;
    }

    public Complement findById(int id) {
        String sql = "SELECT * FROM complement WHERE id = ?";
        try (Connection conn = DatabaseConnection.getConnection();
                PreparedStatement stmt = conn.prepareStatement(sql)) {
            stmt.setInt(1, id);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                return new Complement(rs.getInt("id"), rs.getString("nom"), rs.getDouble("prix"),
                        rs.getString("image"), rs.getString("description"), rs.getBoolean("actif"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }
}
