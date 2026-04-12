using csharp_web.Data;
using csharp_web.Repositories;
using csharp_web.Services;
using Microsoft.EntityFrameworkCore;
using Npgsql;

var builder = WebApplication.CreateBuilder(args);

// ===== MVC =====
builder.Services.AddControllersWithViews();

// ===== CONNECTION STRING =====
var connectionStringEnv = Environment.GetEnvironmentVariable("ConnectionStrings__DefaultConnection");
var connectionString = connectionStringEnv ?? builder.Configuration.GetConnectionString("DefaultConnection");

if (!string.IsNullOrEmpty(connectionString) && connectionString.StartsWith("postgres://"))
{
    var uri = new Uri(connectionString);
    var userInfo = uri.UserInfo.Split(':');
    var builderNpgsql = new NpgsqlConnectionStringBuilder
    {
        Host = uri.Host,
        Port = uri.Port > 0 ? uri.Port : 5432,
        Username = userInfo[0],
        Password = userInfo[1],
        Database = uri.AbsolutePath.TrimStart('/'),
        SslMode = SslMode.Require,
        TrustServerCertificate = true
    };
    connectionString = builderNpgsql.ToString();
}

// ===== DbContext =====
builder.Services.AddDbContext<ApplicationDbContext>(options =>
    options.UseNpgsql(connectionString));

// ===== SESSION =====
builder.Services.AddSession(options =>
{
    options.IdleTimeout = TimeSpan.FromMinutes(30);
    options.Cookie.HttpOnly = true;
    options.Cookie.IsEssential = true;
});

// ===== REPOSITORIES =====
builder.Services.AddScoped<IClientRepository, ClientRepository>();
builder.Services.AddScoped<ICommandeRepository, CommandeRepository>();
builder.Services.AddScoped<ILivreurRepository, LivreurRepository>();

// ===== SERVICES =====
builder.Services.AddScoped<IClientService, ClientService>();
builder.Services.AddScoped<ICommandeService, CommandeService>();
builder.Services.AddScoped<ILivreurService, LivreurService>();
builder.Services.AddScoped<IPanierService, PanierService>();

builder.Services.AddHttpContextAccessor();

var app = builder.Build();

// ===== MIGRATIONS AUTOMATIQUES =====
try
{
    using (var scope = app.Services.CreateScope())
    {
        var context = scope.ServiceProvider.GetRequiredService<ApplicationDbContext>();
        context.Database.Migrate();
        Console.WriteLine("✅ Migrations appliquées avec succès.");
    }
}
catch (Exception ex)
{
    Console.WriteLine("Erreur lors des migrations : " + ex.Message);
}

// ===== INITIALISATION DES DONNÉES =====
try
{
    using (var scope = app.Services.CreateScope())
    {
        var context = scope.ServiceProvider.GetRequiredService<ApplicationDbContext>();
        DbInitializer.Initialize(context);
        Console.WriteLine("✅ Base de données initialisée.");
    }
}
catch (Exception ex)
{
    Console.WriteLine("Erreur lors de l'initialisation de la base : " + ex.Message);
}

// ===== RESET SEQUENCES =====
try
{
    using (var scope = app.Services.CreateScope())
    {
        var context = scope.ServiceProvider.GetRequiredService<ApplicationDbContext>();
        await context.Database.ExecuteSqlRawAsync("SELECT setval('client_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM client));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('burger_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM burger));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('complement_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM complement));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('menu_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM menu));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('zone_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM zone));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('livreur_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM livreur));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('gestionnaire_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM gestionnaire));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('paiement_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM paiement));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('commande_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM commande));");
        await context.Database.ExecuteSqlRawAsync("SELECT setval('ligne_commande_id_seq', (SELECT COALESCE(MAX(id), 0) + 1 FROM ligne_commande));");

        await context.Database.ExecuteSqlRawAsync(@"
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name = 'commande' AND column_name = 'paiement_id') THEN
                    ALTER TABLE commande ADD COLUMN paiement_id INT;
                END IF;
            END $$;
        ");
        Console.WriteLine("✅ Séquences réinitialisées.");
    }
}
catch (Exception ex)
{
    Console.WriteLine("Erreur lors du reset des séquences : " + ex.Message);
}

// ===== PIPELINE HTTP =====
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
    pattern: "{controller=Home}/{action=Index}/{id?}");

app.Run();