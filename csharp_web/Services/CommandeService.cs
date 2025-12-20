using csharp_web.Models;
using csharp_web.Repositories;
using csharp_web.Services;

namespace csharp_web.Services
{
    public interface ICommandeService
    {
        Task<Commande> CreerCommandeAsync(int clientId, List<PanierItem> panierItems, TypeCommande typeCommande, MethodePaiement methodePaiement, string? modeConsommation);
        Task<List<Commande>> GetCommandesClientAsync(int clientId);
        Task<Commande?> GetCommandeByIdAsync(int id);
        Task AnnulerCommandeAsync(int commandeId, int clientId);
        Task<List<LigneCommande>> GetLignesCommandeAsync(int commandeId);
        decimal CalculerTotalCommande(List<LigneCommande> lignes);
    }

    public class CommandeService : ICommandeService
    {
        private readonly ICommandeRepository _commandeRepository;
        private readonly IClientService _clientService;

        public CommandeService(ICommandeRepository commandeRepository, IClientService clientService)
        {
            _commandeRepository = commandeRepository;
            _clientService = clientService;
        }

        public async Task<Commande> CreerCommandeAsync(int clientId, List<PanierItem> panierItems, TypeCommande typeCommande, MethodePaiement methodePaiement, string? modeConsommation)
        {
            // Créer le paiement
            var paiement = new Paiement
            {
                Methode = methodePaiement,
                Date = DateTime.UtcNow,
                Montant = panierItems.Sum(item => item.Prix * item.Quantite)
            };

            var paiementCree = await _commandeRepository.CreatePaiementAsync(paiement);

            // Créer la commande
            var commande = new Commande
            {
                ClientId = clientId,
                Etat = EtatCommande.EnCours,
                Date = DateTime.UtcNow,
                Type = typeCommande, // Garder l'enum, EF devrait gérer la conversion
                PaiementId = paiementCree.Id
            };

            var commandeCreee = await _commandeRepository.CreateCommandeAsync(commande);

            // Créer les lignes de commande
            foreach (var item in panierItems)
            {
                var ligneCommande = new LigneCommande
                {
                    CommandeId = commandeCreee.Id,
                    Quantite = item.Quantite
                };

                // Assigner l'ID selon le type de produit
                switch (item.Type)
                {
                    case TypeProduit.Burger:
                        ligneCommande.BurgerId = item.Id;
                        break;
                    case TypeProduit.Menu:
                        ligneCommande.MenuId = item.Id;
                        break;
                    case TypeProduit.Complement:
                        ligneCommande.ComplementId = item.Id;
                        break;
                }

                await _commandeRepository.CreateLigneCommandeAsync(ligneCommande);
            }

            return commandeCreee;
        }

        public async Task<List<Commande>> GetCommandesClientAsync(int clientId)
        {
            return await _commandeRepository.GetCommandesByClientIdAsync(clientId);
        }

        public async Task<Commande?> GetCommandeByIdAsync(int id)
        {
            return await _commandeRepository.GetCommandeByIdAsync(id);
        }

        public async Task AnnulerCommandeAsync(int commandeId, int clientId)
        {
            var commande = await _commandeRepository.GetCommandeByIdAsync(commandeId);

            if (commande == null || commande.ClientId != clientId)
            {
                throw new Exception("Commande non trouvée ou accès non autorisé");
            }

            if (commande.Etat != EtatCommande.EnCours)
            {
                throw new Exception("Seules les commandes en cours peuvent être annulées");
            }

            commande.Etat = EtatCommande.Annulee;
            await _commandeRepository.UpdateCommandeAsync(commande);
        }

        public async Task<List<LigneCommande>> GetLignesCommandeAsync(int commandeId)
        {
            return await _commandeRepository.GetLignesCommandeAsync(commandeId);
        }

        public decimal CalculerTotalCommande(List<LigneCommande> lignes)
        {
            return lignes.Sum(l => l.PrixUnitaire * l.Quantite);
        }
    }
}