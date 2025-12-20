using csharp_web.Models;
using csharp_web.Services;
using Microsoft.AspNetCore.Mvc;

namespace csharp_web.Controllers
{
    public class PanierController : Controller
    {
        private readonly IPanierService _panierService;

        public PanierController(IPanierService panierService)
        {
            _panierService = panierService;
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
        public IActionResult PasserCommande()
        {
            // TODO: Créer la commande en BDD
            _panierService.ClearCart();
            return RedirectToAction("Index", "Home");
        }
    }
}