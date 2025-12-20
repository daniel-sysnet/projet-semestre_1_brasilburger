using csharp_web.Data;
using csharp_web.Models;

namespace csharp_web.Data
{
    public static class DbInitializer
    {
        public static void Initialize(ApplicationDbContext context)
        {
            // Vérifier si la base est déjà peuplée
            if (context.Burgers.Any())
            {
                return; // La base est déjà initialisée
            }

            // Insérer les données
            var burgers = new Burger[]
            {
                new Burger { Nom = "Cheeseburger", Description = "Burger classique avec fromage", Prix = 5000.00m, Image = "cheeseburger.jpg" },
                new Burger { Nom = "Big Mac", Description = "Burger premium avec double viande", Prix = 7000.00m, Image = "bigmac.jpg" },
                new Burger { Nom = "Chicken Burger", Description = "Burger au poulet grillé", Prix = 6000.00m, Image = "chicken.jpg" }
            };
            context.Burgers.AddRange(burgers);

            var complements = new Complement[]
            {
                new Complement { Nom = "Frites", Description = "Frites croustillantes", Prix = 2000.00m, Image = "frites.jpg" },
                new Complement { Nom = "Riz", Description = "Riz parfumé", Prix = 1500.00m, Image = "riz.jpg" },
                new Complement { Nom = "Sauce Ketchup", Description = "Sauce ketchup maison", Prix = 500.00m, Image = "ketchup.jpg" },
                new Complement { Nom = "Coca Cola", Description = "Boisson gazeuse Coca Cola", Prix = 1500.00m, Image = "coca.jpg" },
                new Complement { Nom = "Eau", Description = "Eau minérale", Prix = 1000.00m, Image = "eau.jpg" }
            };
            context.Complements.AddRange(complements);

            var menus = new Menu[]
            {
                new Menu { Nom = "Menu Cheeseburger", Description = "Menu complet avec Cheeseburger", Prix = 8500.00m, Image = "menu_cheese.jpg" },
                new Menu { Nom = "Menu Big Mac", Description = "Menu premium avec Big Mac", Prix = 10500.00m, Image = "menu_bigmac.jpg" }
            };
            context.Menus.AddRange(menus);

            var clients = new Client[]
            {
                new Client { Nom = "Dupont", Prenom = "Jean", Telephone = "771234567" },
                new Client { Nom = "Martin", Prenom = "Marie", Telephone = "772345678" }
            };
            context.Clients.AddRange(clients);

            var zones = new Zone[]
            {
                new Zone { Nom = "Centre-ville", Prix = 2000.00m },
                new Zone { Nom = "Banlieue Nord", Prix = 2500.00m }
            };
            context.Zones.AddRange(zones);

            var livreurs = new Livreur[]
            {
                new Livreur { Nom = "Livreur1", Prenom = "Paul", Telephone = "773456789", ZoneId = 1 },
                new Livreur { Nom = "Livreur2", Prenom = "Sophie", Telephone = "774567890", ZoneId = 2 }
            };
            context.Livreurs.AddRange(livreurs);

            var gestionnaires = new Gestionnaire[]
            {
                new Gestionnaire { Nom = "Admin", Prenom = "Admin", Telephone = "775678901", Login = "admin", Password = "admin123" }
            };
            context.Gestionnaires.AddRange(gestionnaires);

            var paiements = new Paiement[]
            {
                new Paiement { Date = new DateTime(2025, 12, 13), Montant = 8500.00m, Methode = MethodePaiement.Wave },
                new Paiement { Date = new DateTime(2025, 12, 13), Montant = 10500.00m, Methode = MethodePaiement.OM }
            };
            context.Paiements.AddRange(paiements);

            var commandes = new Commande[]
            {
                new Commande { ClientId = 1, Etat = EtatCommande.Terminee, Date = new DateTime(2025, 12, 13), Type = TypeCommande.Livraison, ZoneId = 1, LivreurId = 1, PaiementId = 1 },
                new Commande { ClientId = 2, Etat = EtatCommande.EnCours, Date = new DateTime(2025, 12, 13), Type = TypeCommande.SurPlace, PaiementId = 2 }
            };
            context.Commandes.AddRange(commandes);

            var ligneCommandes = new LigneCommande[]
            {
                new LigneCommande { CommandeId = 1, BurgerId = 1, Quantite = 1 },
                new LigneCommande { CommandeId = 1, MenuId = 1, ComplementId = 1, Quantite = 1 },
                new LigneCommande { CommandeId = 1, MenuId = 1, ComplementId = 4, Quantite = 1 }
            };
            context.LigneCommandes.AddRange(ligneCommandes);

            context.SaveChanges();
        }
    }
}