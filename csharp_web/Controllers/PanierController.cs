using csharp_web.Models;
using csharp_web.Services;
using Microsoft.AspNetCore.Mvc;

namespace csharp_web.Controllers
{
    public class PanierController : Controller
    {
        private readonly IPanierService _panierService;
        private readonly ICommandeService _commandeService;

        public PanierController(IPanierService panierService, ICommandeService commandeService)
        {
            _panierService = panierService;
            _commandeService = commandeService;
        }

        public IActionResult Index()
        {
            var items = _panierService.GetCartItems();
            ViewBag.Total = _panierService.GetTotal();
            ViewBag.ModeConsommation = _panierService.GetModeConsommation();
            ViewBag.MethodePaiement = _panierService.GetMethodePaiement();
            return View(items);
        }

        [HttpPost]
        public IActionResult AddToCart(TypeProduit type, int id, string nom, decimal prix, string image)
        {
            _panierService.AddToCart(type, id, nom, prix, image);
            return RedirectToAction("Index", "Home");
        }

        [HttpPost]
        public IActionResult UpdateQuantity(int itemId, int quantity)
        {
            _panierService.UpdateQuantity(itemId, quantity);
            return RedirectToAction("Index");
        }

        [HttpPost]
        public IActionResult RemoveFromCart(int itemId)
        {
            _panierService.RemoveFromCart(itemId);
            return RedirectToAction("Index");
        }

        [HttpPost]
        public IActionResult SetModeConsommation(string mode)
        {
            _panierService.SetModeConsommation(mode);
            return RedirectToAction("Index");
        }

        [HttpPost]
        public IActionResult SetMethodePaiement(MethodePaiement methode)
        {
            _panierService.SetMethodePaiement(methode);
            return RedirectToAction("Index");
        }

        [HttpPost]
        public async Task<IActionResult> PasserCommande()
        {
            var clientId = HttpContext.Session.GetInt32("ClientId");
            Console.WriteLine($"PasserCommande - ClientId: {clientId}");

            if (!clientId.HasValue)
            {
                Console.WriteLine("PasserCommande - Utilisateur non connecté, redirection vers Login");
                return RedirectToAction("Login", "Account");
            }

            var panierItems = _panierService.GetCartItems();
            Console.WriteLine($"PasserCommande - Nombre d'articles dans le panier: {panierItems.Count}");

            if (!panierItems.Any())
            {
                Console.WriteLine("PasserCommande - Panier vide, redirection vers Index");
                return RedirectToAction("Index");
            }

            // Convertir le mode de consommation en TypeCommande
            var modeConsommation = _panierService.GetModeConsommation();
            var typeCommande = modeConsommation switch
            {
                "Sur place" => TypeCommande.SurPlace,
                "À emporter" => TypeCommande.AEmporter,
                "Livraison" => TypeCommande.Livraison,
                _ => TypeCommande.SurPlace
            };

            var methodePaiement = _panierService.GetMethodePaiement();
            Console.WriteLine($"PasserCommande - Mode: {modeConsommation}, Type: {typeCommande}, Paiement: {methodePaiement}");

            try
            {
                Console.WriteLine("PasserCommande - Création de la commande...");
                await _commandeService.CreerCommandeAsync(clientId.Value, panierItems, typeCommande, methodePaiement, modeConsommation);
                _panierService.ClearCart();
                TempData["SuccessMessage"] = "Votre commande a été passée avec succès !";
                Console.WriteLine("PasserCommande - Commande créée avec succès, redirection vers Commande/Index");
                return RedirectToAction("Index", "Commande");
            }
            catch (Exception ex)
            {
                Console.WriteLine($"PasserCommande - Erreur: {ex.Message}");
                TempData["ErrorMessage"] = "Erreur lors de la création de la commande : " + ex.Message;
                return RedirectToAction("Index");
            }
        }
    }
}