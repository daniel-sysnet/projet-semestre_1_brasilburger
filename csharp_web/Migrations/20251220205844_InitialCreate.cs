using System;
using Microsoft.EntityFrameworkCore.Migrations;
using Npgsql.EntityFrameworkCore.PostgreSQL.Metadata;

#nullable disable

namespace csharp_web.Migrations
{
    /// <inheritdoc />
    public partial class InitialCreate : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.CreateTable(
                name: "burger",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    description = table.Column<string>(type: "text", nullable: false),
                    prix = table.Column<decimal>(type: "numeric", nullable: false),
                    image = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_burger", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "client",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    prenom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    telephone = table.Column<string>(type: "character varying(20)", maxLength: 20, nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_client", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "complement",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    description = table.Column<string>(type: "text", nullable: false),
                    prix = table.Column<decimal>(type: "numeric", nullable: false),
                    image = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_complement", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "gestionnaire",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    prenom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    telephone = table.Column<string>(type: "character varying(20)", maxLength: 20, nullable: false),
                    login = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    password = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_gestionnaire", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "menu",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    description = table.Column<string>(type: "text", nullable: false),
                    prix = table.Column<decimal>(type: "numeric", nullable: false),
                    image = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_menu", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "commande",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    client_id = table.Column<int>(type: "integer", nullable: true),
                    etat = table.Column<string>(type: "text", nullable: false),
                    date = table.Column<DateTime>(type: "timestamp with time zone", nullable: false),
                    total = table.Column<decimal>(type: "numeric", nullable: false),
                    type = table.Column<string>(type: "text", nullable: false),
                    zone_id = table.Column<int>(type: "integer", nullable: true),
                    livreur_id = table.Column<int>(type: "integer", nullable: true),
                    paiement_id = table.Column<int>(type: "integer", nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_commande", x => x.id);
                    table.ForeignKey(
                        name: "FK_commande_client_client_id",
                        column: x => x.client_id,
                        principalTable: "client",
                        principalColumn: "id");
                });

            migrationBuilder.CreateTable(
                name: "lignecommande",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    commande_id = table.Column<int>(type: "integer", nullable: false),
                    burger_id = table.Column<int>(type: "integer", nullable: true),
                    menu_id = table.Column<int>(type: "integer", nullable: true),
                    complement_id = table.Column<int>(type: "integer", nullable: true),
                    quantite = table.Column<int>(type: "integer", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_lignecommande", x => x.id);
                    table.ForeignKey(
                        name: "FK_lignecommande_burger_burger_id",
                        column: x => x.burger_id,
                        principalTable: "burger",
                        principalColumn: "id");
                    table.ForeignKey(
                        name: "FK_lignecommande_commande_commande_id",
                        column: x => x.commande_id,
                        principalTable: "commande",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                    table.ForeignKey(
                        name: "FK_lignecommande_complement_complement_id",
                        column: x => x.complement_id,
                        principalTable: "complement",
                        principalColumn: "id");
                    table.ForeignKey(
                        name: "FK_lignecommande_menu_menu_id",
                        column: x => x.menu_id,
                        principalTable: "menu",
                        principalColumn: "id");
                });

            migrationBuilder.CreateTable(
                name: "paiement",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    commande_id = table.Column<int>(type: "integer", nullable: false),
                    date = table.Column<DateTime>(type: "timestamp with time zone", nullable: false),
                    montant = table.Column<decimal>(type: "numeric", nullable: false),
                    methode = table.Column<string>(type: "text", nullable: false)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_paiement", x => x.id);
                    table.ForeignKey(
                        name: "FK_paiement_commande_commande_id",
                        column: x => x.commande_id,
                        principalTable: "commande",
                        principalColumn: "id",
                        onDelete: ReferentialAction.Cascade);
                });

            migrationBuilder.CreateTable(
                name: "livreur",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    prenom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    telephone = table.Column<string>(type: "character varying(20)", maxLength: 20, nullable: false),
                    ZoneId = table.Column<int>(type: "integer", nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_livreur", x => x.id);
                });

            migrationBuilder.CreateTable(
                name: "zone",
                columns: table => new
                {
                    id = table.Column<int>(type: "integer", nullable: false)
                        .Annotation("Npgsql:ValueGenerationStrategy", NpgsqlValueGenerationStrategy.IdentityByDefaultColumn),
                    nom = table.Column<string>(type: "character varying(255)", maxLength: 255, nullable: false),
                    prix = table.Column<decimal>(type: "numeric", nullable: false),
                    LivreurId = table.Column<int>(type: "integer", nullable: true)
                },
                constraints: table =>
                {
                    table.PrimaryKey("PK_zone", x => x.id);
                    table.ForeignKey(
                        name: "FK_zone_livreur_LivreurId",
                        column: x => x.LivreurId,
                        principalTable: "livreur",
                        principalColumn: "id");
                });

            migrationBuilder.CreateIndex(
                name: "IX_commande_client_id",
                table: "commande",
                column: "client_id");

            migrationBuilder.CreateIndex(
                name: "IX_commande_livreur_id",
                table: "commande",
                column: "livreur_id");

            migrationBuilder.CreateIndex(
                name: "IX_commande_paiement_id",
                table: "commande",
                column: "paiement_id");

            migrationBuilder.CreateIndex(
                name: "IX_commande_zone_id",
                table: "commande",
                column: "zone_id");

            migrationBuilder.CreateIndex(
                name: "IX_lignecommande_burger_id",
                table: "lignecommande",
                column: "burger_id");

            migrationBuilder.CreateIndex(
                name: "IX_lignecommande_commande_id",
                table: "lignecommande",
                column: "commande_id");

            migrationBuilder.CreateIndex(
                name: "IX_lignecommande_complement_id",
                table: "lignecommande",
                column: "complement_id");

            migrationBuilder.CreateIndex(
                name: "IX_lignecommande_menu_id",
                table: "lignecommande",
                column: "menu_id");

            migrationBuilder.CreateIndex(
                name: "IX_livreur_ZoneId",
                table: "livreur",
                column: "ZoneId");

            migrationBuilder.CreateIndex(
                name: "IX_paiement_commande_id",
                table: "paiement",
                column: "commande_id");

            migrationBuilder.CreateIndex(
                name: "IX_zone_LivreurId",
                table: "zone",
                column: "LivreurId");

            migrationBuilder.AddForeignKey(
                name: "FK_commande_livreur_livreur_id",
                table: "commande",
                column: "livreur_id",
                principalTable: "livreur",
                principalColumn: "id");

            migrationBuilder.AddForeignKey(
                name: "FK_commande_paiement_paiement_id",
                table: "commande",
                column: "paiement_id",
                principalTable: "paiement",
                principalColumn: "id");

            migrationBuilder.AddForeignKey(
                name: "FK_commande_zone_zone_id",
                table: "commande",
                column: "zone_id",
                principalTable: "zone",
                principalColumn: "id");

            migrationBuilder.AddForeignKey(
                name: "FK_livreur_zone_ZoneId",
                table: "livreur",
                column: "ZoneId",
                principalTable: "zone",
                principalColumn: "id");
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DropForeignKey(
                name: "FK_commande_client_client_id",
                table: "commande");

            migrationBuilder.DropForeignKey(
                name: "FK_commande_livreur_livreur_id",
                table: "commande");

            migrationBuilder.DropForeignKey(
                name: "FK_zone_livreur_LivreurId",
                table: "zone");

            migrationBuilder.DropForeignKey(
                name: "FK_commande_paiement_paiement_id",
                table: "commande");

            migrationBuilder.DropTable(
                name: "gestionnaire");

            migrationBuilder.DropTable(
                name: "lignecommande");

            migrationBuilder.DropTable(
                name: "burger");

            migrationBuilder.DropTable(
                name: "complement");

            migrationBuilder.DropTable(
                name: "menu");

            migrationBuilder.DropTable(
                name: "client");

            migrationBuilder.DropTable(
                name: "livreur");

            migrationBuilder.DropTable(
                name: "paiement");

            migrationBuilder.DropTable(
                name: "commande");

            migrationBuilder.DropTable(
                name: "zone");
        }
    }
}
