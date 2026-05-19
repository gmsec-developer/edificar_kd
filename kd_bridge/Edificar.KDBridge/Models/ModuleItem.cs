namespace Edificar.KDBridge.Models;

public class ModuleItem
{
    public int Number { get; set; }

    public string Reference { get; set; } = "";

    public string Description { get; set; } = "";

    public double Quantity { get; set; }

    public double Dx { get; set; }

    public double Dy { get; set; }

    public double Dz { get; set; }

    public double Pvp { get; set; }

    public double Total { get; set; }
}