using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using csharp_web.Models;
using csharp_web.Data;
using Microsoft.EntityFrameworkCore;

namespace csharp_web.Controllers;

public class HomeController : Controller
{
    private readonly ApplicationDbContext _context;

    public HomeController(ApplicationDbContext context)
    {
        _context = context;
    }

    public async Task<IActionResult> Index(string filter = "all")
    {
        ViewBag.Filter = filter;

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
