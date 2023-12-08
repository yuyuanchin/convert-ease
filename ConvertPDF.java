import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;

import java.io.File;
import java.io.IOException;

public class ConvertPDF {
    private static final String PDFBOX_JAR_PATH = "/var/www/html/ConvertEase/PDFBox/pdfbox-app-2.0.30.jar";

    public static void main(String[] args) {
        try {
            // Set the classpath dynamically to include the PDFBox JAR
            String currentClasspath = System.getProperty("java.class.path");
            String newClasspath = currentClasspath + File.pathSeparator + PDFBOX_JAR_PATH;
            System.setProperty("java.class.path", newClasspath);

            // Path to the PDF file you want to convert
            String pdfFilePath = "/path/to/your/input.pdf";
            // Output path for the text file
            String textOutputPath = "/path/to/your/output.txt";

            // Convert PDF to text
            convertPdfToText(pdfFilePath, textOutputPath);

            System.out.println("Conversion successful!");
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static void convertPdfToText(String pdfFilePath, String textOutputPath) throws IOException {
        try (PDDocument document = PDDocument.load(new File(pdfFilePath))) {
            PDFTextStripper pdfTextStripper = new PDFTextStripper();
            String text = pdfTextStripper.getText(document);
            // Write the text to a file
            // In a real-world scenario, you might want to process or return the text differently
            // Here, we simply write it to a text file for demonstration purposes
            org.apache.commons.io.FileUtils.writeStringToFile(new File(textOutputPath), text, "UTF-8");
        }
    }
}