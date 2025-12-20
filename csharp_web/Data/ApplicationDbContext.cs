using Microsoft.EntityFrameworkCore;
using csharp_web.Models;

namespace csharp_web.Data
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options) : base(options) { }

        public DbSet<Burger> Burgers { get; set; }
        public DbSet<Complement> Complements { get; set; }
        public DbSet<Menu> Menus { get; set; }
        public DbSet<Client> Clients { get; set; }
        public DbSet<Zone> Zones { get; set; }
        public DbSet<Livreur> Livreurs { get; set; }
        public DbSet<Gestionnaire> Gestionnaires { get; set; }
        public DbSet<Paiement> Paiements { get; set; }
        public DbSet<Commande> Commandes { get; set; }
        public DbSet<LigneCommande> LigneCommandes { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            // Configurations supplémentaires si nécessaire
            modelBuilder.Entity<Commande>()
                .Property(c => c.Etat)
                .HasConversion<string>();

            modelBuilder.Entity<Commande>()
                .Property(c => c.Type)
                .HasConversion<string>();

            modelBuilder.Entity<Paiement>()
                .Property(p => p.Methode)
                .HasConversion<string>();
        }
    }
}