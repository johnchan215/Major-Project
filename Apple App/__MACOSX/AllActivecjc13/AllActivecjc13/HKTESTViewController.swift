//
//  HKTESTViewController.swift
//  AllActivecjc13
//
//  Created by administrator on 08/05/2017.
//  Copyright © 2017 cjc13. All rights reserved.
//

import UIKit
import HealthKit

class HKTESTViewController: UIViewController {

    @IBOutlet weak var enableHKButton: UIButton!
    
    lazy var healthStore = HKHealthStore()
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        
        // Hide button after enabled
        enableHKButton.isHidden = !HKHealthStore.isHealthDataAvailable()
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    // Enable HealthKit
    @IBAction func enableHK(_ sender: Any) {
        if HKHealthStore.isHealthDataAvailable() {
            var readTypes = Set<HKSampleType>()
            // Read from steps, floors, distance, and calories
            readTypes.insert(HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.stepCount)!)
            readTypes.insert(HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.flightsClimbed)!)
            readTypes.insert(HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.distanceWalkingRunning)!)
            readTypes.insert(HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.activeEnergyBurned)!)
        
            // Request authorisation from user
            healthStore.requestAuthorization(toShare: nil, read: readTypes) { (success, error) -> Void in
                if success {
                    print("success")
                } else {
                    print("failure")
                }
            
                // Print error
                if let error = error {
                    print(error)
                }
            }
        } else {
            return
        }
    }
    
    // http://stackoverflow.com/questions/41989750/swift-3-calling-step-count-query-from-health-kit/41989834
    // Getting today steps
    func getTodaySteps(input: String, completion: @escaping (_ stepRetrieved: Double) -> Void) {
        // Defining step count
        let stepCount = HKSampleType.quantityType(forIdentifier: HKQuantityTypeIdentifier.stepCount)
        
        // Defining start of date
        let date = NSDate()
        let calendar = Calendar(identifier: Calendar.Identifier.gregorian)
        let newDate = calendar.startOfDay(for: date as Date)
        let yesterday = NSCalendar.current.date(byAdding: .day, value: -1, to: Date())
        let now = Date()
        
        // Defining predicate and intervals
        let predicate = HKQuery.predicateForSamples(withStart: newDate as Date, end: NSDate() as Date, options: .strictStartDate)
        let interval: NSDateComponents = NSDateComponents()
        
        interval.day = 1
        
        // Query sum of step count with date, predicate, and interval
        let query = HKStatisticsCollectionQuery(quantityType: stepCount!, quantitySamplePredicate: predicate, options: [.cumulativeSum], anchorDate: newDate as Date, intervalComponents: interval as DateComponents)
       
        query.initialResultsHandler = {query, results, error in
        
            if error != nil {
                // Error
                return
            }
            
            // Print the steps from yesterday to now period
            if let myResults = results {
                myResults.enumerateStatistics(from: yesterday! as Date, to: now as Date) {
                    statistic, stop in
                    
                    if let quantity = statistic.sumQuantity() {
                        let steps = quantity.doubleValue(for: HKUnit.count())
                        
                        print("Steps = \(steps)")
                        completion(steps)
                    }
                }
            }
        }
        healthStore.execute(query)
    }
    
    
    @IBAction func getSteps(_ sender: Any) {
        getTodaySteps(input: "test") { (stepsRetrieved) in
            print(stepsRetrieved)
        }
        
    }
    

}
