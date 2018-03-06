//
//  HealthViewController.swift
//  AllActivecjc13
//
//  Created by administrator on 27/04/2017.
//  Copyright © 2017 cjc13. All rights reserved.
//

import UIKit

class HealthViewController: UIViewController {

    @IBOutlet weak var stepsField: UITextField!
    @IBOutlet weak var floorsField: UITextField!
    @IBOutlet weak var distanceField: UITextField!
    @IBOutlet weak var caloriesField: UITextField!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
        
        // Look for single or multiple taps
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(HealthViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    // Function to dismiss keyboard when clicked out
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    // Function for the alert popup messages
    func alertMessage(userMessage: String) {
        let alert = UIAlertController(title: "Alert", message: userMessage, preferredStyle: UIAlertControllerStyle.alert)
        
        let confirm = UIAlertAction(title: "Confirm", style: UIAlertActionStyle.default, handler: nil)
        
        alert.addAction(confirm)
        
        self.present(alert, animated: true, completion: nil)
    }
    
    @IBAction func syncButton(_ sender: Any) {
        
        let steps = stepsField.text
        let floors = floorsField.text
        let distance = distanceField.text
        let calories = caloriesField.text
        
        if((steps?.isEmpty)! || (floors?.isEmpty)! || (distance?.isEmpty)! || (calories?.isEmpty)!) {
            // Display alert message
            alertMessage(userMessage: "All fields are required")
            return
        }
        
        // Send user data to script
        let url = NSURL(string:"http://users.aber.ac.uk/cjc13/major_project/AppData.php")
        let request = NSMutableURLRequest(url: url! as URL)
        request.httpMethod = "POST"
        
        let postString = "steps="+steps!+"&floors="+floors!+"&distance="+distance!+"&calories="+calories!
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        // Crating task to send request
        let task = URLSession.shared.dataTask(with: request as URLRequest) {
            data, response, error in
            
            if error != nil {
                print("error=\(String(describing: error))")
                return
            }
            
            // Parsing response
            do {
                let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                
                // Parsing JSON
                if let parseJSON = json {
                    var msg : String!
                    msg = parseJSON["status"] as! String?
                    print("result: \(msg)")
                    
                    // Send to main thread
                    DispatchQueue.main.async {
                        // Login Success
                        if(msg == "Success") {
                            self.alertMessage(userMessage: "Activity data successfully updated")
                        } else {
                            self.alertMessage(userMessage: "Could not update activity data")
                        }
                    }
                }
                
            } catch {
               print(error)
                
            }
        }
        task.resume()
            
    }
    
}
