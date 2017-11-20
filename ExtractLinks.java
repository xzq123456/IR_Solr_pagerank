import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

import java.io.File;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.HashSet;
import java.util.Set;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileWriter;
import java.util.HashMap;

/**
 * Example program to list links from a URL.
 */
public class ExtractLinks {
	
	
    public static void main(String[] args) throws IOException {
    	
    	String filePath = "C:/Users/Monika/Desktop/CS572 - Info. Retrieval/My Assignments/hw4/crawl_data/NYD/NYD Map.csv";
	    HashMap<String, String> fileUrlMap = new HashMap<String, String>();
	    HashMap<String, String> urlFileMap = new HashMap<String, String>();
	    
	    String line;
	    BufferedReader reader = new BufferedReader(new FileReader(filePath));
	    while ((line = reader.readLine()) != null)
	    {
	        String[] parts = line.split(",", 2);
	        if (parts.length >= 2)
	        {
	            String key = parts[0];
	            String value = parts[1];
	            fileUrlMap.put(key, value);
	            urlFileMap.put(value,key);
	        } 
	    }
	    
	    reader.close();
	    
	    BufferedWriter buf = new BufferedWriter(new FileWriter("C:/Users/Monika/Desktop/CS572 - Info. Retrieval/My Assignments/hw4/edges_try.csv"));
	    
	    
    	File dir = new File("C:/Users/Monika/Desktop/CS572 - Info. Retrieval/My Assignments/hw4/crawl_data/NYD/NYD/");
    	Set<String> edges = new HashSet<String>();
    	int count=0;
    	for(File file: dir.listFiles())
    	{
    		count++;
    		String fileName = file.getName();
    		Document doc = Jsoup.parse(file,"UTF-8",fileUrlMap.get(fileName));
    		Elements links = doc.select("a[href]");
//            Elements media = doc.select("[src]");
            Elements imports = doc.select("link[href]");
            
            buf.write(fileUrlMap.get(fileName)+",");
            	for(Element link: links)
            	{
            		String url = link.attr("href").trim();
            		if(urlFileMap.containsKey(url)){
//            			edges.add(fileName+" "+urlFileMap.get(url));
            			buf.write(url+",");
            		}
            	}
            	buf.newLine();
    	}

//    
//    	    PrintWriter out = new PrintWriter(new FileWriter("C:/Users/Monika/Desktop/CS572 - Info. Retrieval/My Assignments/hw4/edges_try.txt"));
//    	    
//    	    for(String s : edges)
//        	{
//        		out.println(s);
//        	}
//    	    out.flush();
//    	    out.close();
    	    buf.close();
    	    
    	System.out.println(count);
    	System.out.println(edges.size());

    }
}