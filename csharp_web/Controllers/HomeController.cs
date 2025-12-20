using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using csharp_web.Models;
using csharp_web.Data;
using Microsoft.EntityFrameworkCore;
using csharp_web.Services;

namespace csharp_web.Controllers;

public class HomeController : Controller
{
    private readonly ApplicationDbContext _context;
    private readonly IPanierService _panierService;

    public HomeController(ApplicationDbContext context, IPanierService panierService)
    {
        _context = context;
        _panierService = panierService;
    }

    public async Task<IActionResult> Index(string filter = "all")
    {
        ViewBag.Filter = filter;
        ViewBag.CartItemCount = _panierService.GetCartItemCount();

        if (filter == "all" || filter == "burgers")
        {
            ViewBag.Burgers = await _context.Burgers.ToListAsync();
        }

        if (filter == "all" || filter == "complements")
        {
            ViewBag.Complements = await _context.Complements.ToListAsync();
        }

        if (filter == "all" || filter == "menus")
        {
            ViewBag.Menus = await _context.Menus.ToListAsync();
        }

        return View();
    }

    [HttpPost]
    public IActionResult AddToCart(TypeProduit type, int id, string nom, decimal prix, string image)
    {
        _panierService.AddToCart(type, id, nom, prix, image);
        return Json(new { success = true, itemCount = _panierService.GetCartItemCount() });
    }

    public IActionResult Privacy()
    {
        return View();
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}
