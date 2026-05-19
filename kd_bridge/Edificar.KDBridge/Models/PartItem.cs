namespace Edificar.KDBridge.Models;

public class PartItem
{
    public int LineNumber { get; set; }

    public int ModuleNumber { get; set; }

    public string ModuleReference { get; set; } = "";

    public int TypeCode { get; set; }

    public string Part { get; set; } = "";

    public double Quantity { get; set; }

    public double Length { get; set; }

    public double Width { get; set; }

    public double Thickness { get; set; }

    public string MaterialCode { get; set; } = "";

    public string MaterialRaw { get; set; } = "";

    public string Material { get; set; } = "";

    public string ColorCode { get; set; } = "";

    public string ColorDescription { get; set; } = "";
}