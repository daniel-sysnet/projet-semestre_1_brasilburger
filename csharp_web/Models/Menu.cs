using System.ComponentModel.DataAnnotations;

namespace csharp_web.Models
{
    public class Menu
    {
        [Key]
        public int Id { get; set; }

        [Required]
        [StringLength(255)]
        public string Nom { get; set; }

        public string Description { get; set; }

        [Required]
        public decimal Prix { get; set; }

        [StringLength(255)]
        public string Image { get; set; }
    }
}