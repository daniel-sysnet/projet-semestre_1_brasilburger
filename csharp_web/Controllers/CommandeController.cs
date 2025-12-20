using Microsoft.AspNetCore.Mvc;
using csharp_web.Models;
using csharp_web.Services;
using Microsoft.AspNetCore.Http;

namespace csharp_web.Controllers
{
    public class CommandeController : Controller
    {
        private readonly ICommandeService _commandeService;
        private readonly IPanierService _panierService;
        private readonly IClientService _clientService;

        public CommandeController(ICommandeService commandeService, IPanierService panierService, IClientService clientService)
        {
            _commandeService = commandeService;
            _panierService = panierService;
            _clientService = clientService;
        }

        public async Task<IActionResult> Index()
        {
            // Vérifier si l'utilisateur est connecté
            var clientId = HttpContext.Session.GetInt32("ClientId");
            if (!clientId.HasValue)
            {
                return View();
            }

            var commandes = await _commandeService.GetCommandesClientAsync(clientId.Value);

            // Pour chaque commande, récupérer les lignes et calculer le total
            var commandesAvecDetails = new List<dynamic>();
            foreach (var commande in commandes)
            {
                var lignes = await _commandeService.GetLignesCommandeAsync(commande.Id);
                var total = _commandeService.CalculerTotalCommande(lignes);

                commandesAvecDetails.Add(new
                {
                    Commande = commande,
                    Lignes = lignes,
                    Total = total
                });
            }

            ViewBag.CommandesAvecDetails = commandesAvecDetails;
            return View();
        }

        [HttpPost]
        public async Task<IActionResult> AnnulerCommande(int commandeId)
        {
            var clientId = HttpContext.Session.GetInt32("ClientId");
            if (!clientId.HasValue)
            {
                return Json(new { success = false, message = "Utilisateur non connecté" });
            }

            try
            {
                await _commandeService.AnnulerCommandeAsync(commandeId, clientId.Value);
                return Json(new { success = true });
            }
            catch (Exception ex)
            {
                return Json(new { success = false, message = ex.Message });
            }
        }
    }
}