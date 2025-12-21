using csharp_web.Data;
using csharp_web.Repositories;
using csharp_web.Services;
using Microsoft.EntityFrameworkCore;

var builder = WebApplication.CreateBuilder(args);

// MVC
builder.Services.AddControllersWithViews();

// PostgreSQL (Render compatible)
var connectionString = Environment.GetEnvironmentVariable("DATABASE_URL") ?? builder.Configuration.GetConnectionString("DefaultConnection");

// Fix incomplete sslmode in DATABASE_URL
if (connectionString != null && connectionString.Contains("?sslmode") && !connectionString.Contains("sslmode=require"))
{
    connectionString = connectionString.Replace("?sslmode", "?sslmode=require");
}

builder.Services.AddDbContext<ApplicationDbContext>(options =>
    options.UseNpgsql(connectionString));

// Session
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(30);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});

// Repositories
builder.Services.AddScoped<IClientRepository, ClientRepository>();
builder.Services.AddScoped<ICommandeRepository, CommandeRepository>();
builder.Services.AddScoped<ILivreurRepository, LivreurRepository>();

// Services
builder.Services.AddScoped<IClientService, ClientService>();
builder.Services.AddScoped<ICommandeService, CommandeService>();
builder.Services.AddScoped<ILivreurService, LivreurService>();
builder.Services.AddScoped<IPanierService, PanierService>();

builder.Services.AddHttpContextAccessor();

var app = builder.Build();


// ===== MIGRATIONS AUTOMATIQUES (RENDER) =====
using (var scope = app.Services.CreateScope())
{
    var context = scope.ServiceProvider.GetRequiredService<ApplicationDbContext>();
    context.Database.Migrate();
}


// Pipeline HTTP
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Home/Error");
    app.UseHsts();
}

app.UseHttpsRedirection();
app.UseStaticFiles();

app.UseRouting();

app.UseSession();
app.UseAuthorization();

app.MapControllerRoute(
    name: "default",
    pattern: "{controller=Account}/{action=Login}/{id?}");

app.Run();
