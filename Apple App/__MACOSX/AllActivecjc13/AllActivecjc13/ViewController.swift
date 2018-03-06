//
//  ViewController.swift
//  AllActivecjc13
//
//  Created by administrator on 18/04/2017.
//  Copyright © 2017 cjc13. All rights reserved.
//

import UIKit

class ViewController: UIViewController {

    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewDidAppear(_ animated: Bool) {
        let isUserLoggedIn = UserDefaults.standard.bool(forKey: "isUserLoggedIn")
        
        if(!isUserLoggedIn) {
            self.performSegue(withIdentifier: "loginView", sender: self)
        }
    }
    
    // LOGOUT BUTTON FUNCTION
    @IBAction func logoutButton(_ sender: Any) {
        let url = NSURL(string:"http://users.aber.ac.uk/cjc13/major_project/AppLogout.php");
        let request = NSMutableURLRequest(url: url! as URL)
        request.httpMethod = "POST"
        
        // Creating task to send post request
        let logoutTask = URLSession.shared.dataTask(with: request as URLRequest) {
            data, response, error in
            
            if error != nil {
                print("error=\(String(describing: error))")
                return
            }
            
            // Parsing Response
            do {
                // Converting Response to NSDictionary
                let logoutJson = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                
                // Parsing JSON
                if let parseJSON = logoutJson {
                    var msg: String!
                    msg = parseJSON["code"] as! String?
                    print("result: \(msg)")
                    
                    // Send to main thread
                    DispatchQueue.main.async {
                        if(msg == "0") {
                            UserDefaults.standard.set(false, forKey: "isUserLoggedIn")
                            UserDefaults.standard.synchronize();
                            
                            self.performSegue(withIdentifier: "loginView", sender: self)
                            }
                    }
                }
            } catch {
                print(error)
            }
        }
        logoutTask.resume()
        
    }
    
}

