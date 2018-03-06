//
//  HealthKitManager.swift
//  AllActivecjc13
//
//  Created by administrator on 26/04/2017.
//  Copyright © 2017 cjc13. All rights reserved.
//

import Foundation
import HealthKit

class HealthKitManager {
    
    
    
//    https://medium.com/@missyalienn/a-walk-through-healthkit-10434d33ff87
//    let storage = HKHealthStore()
//    
//    init() {
//        checkAuthorization()
//    }
//    
//    func checkAuthorization() -> Bool {
//        var isEnabled = true
//        
//        if HKHealthStore.isHealthDataAvailable() {
//            let dataToRead = NSSet(object: HKQuantityType.HKQuantityTypeIdentifier(HKQuantityTypeIdentifier.stepCount)!)
//            
//            storage.requestAuthorizationToShareTypes(nil, readTypes: dataToRead) { (success, error) -> Void in
//                isEnabled = success
//            }
//        } else {
//            isEnabled = false
//        }
//        return isEnabled
//    }
    
    ///////////////////////////////////////////////////////////////////
    
//    https://www.raywenderlich.com/86336/ios-8-healthkit-swift-getting-started
//    let healthKitStore:HKHealthStore = HKHealthStore()
//    
//    func authorizeHealthKit() -> Bool {
//        var isEnabled = true
//        
//        // Place healthkit types in an array to be read later
//        let healthKitTypes : Set = [
//            HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.stepCount)!,
//            HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.flightsClimbed)!,
//            HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.distanceWalkingRunning)!,
//            HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.activeEnergyBurned)!,
//            HKQuantityType.workoutType()
//        ]
//            
//        // If Health App is not availble in the device
//        if HKHealthStore.isHealthDataAvailable() {
//            healthKitStore.requestAuthorization(toShare: nil, read: healthKitTypes) { (success, error) -> Void in
//                isEnabled = success
//            }
//        } else {
//                isEnabled = true
//            
//        }
//            return isEnabled
//    }
    
   /////////////////////////////////////////////////////////////////////
    
//    http://crunchybagel.com/recording-workouts-in-healthkit/  
//    class var sharedInstance: HealthKitManager {
//        struct Singleton {
//            static let instance = HealthKitManager()
//        }
//        return Singleton.instance
//    }
//    
//    let healthStore: HKHealthStore? = {
//        if HKHealthStore.isHealthDataAvailable() {
//            return HKHealthStore()
//        } else {
//            return nil
//        }
//    }()
//    
//    let stepsCount = HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.stepCount)
//    let stepsUnit = HKUnit.count()
//    
//    let floorCount = HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.flightsClimbed)
//    let floorUnit = HKUnit.count()
//    
//    let distanceCount = HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.distanceWalkingRunning)
//    let distanceUnit = HKUnit.meterUnit(with: .kilo)
//    
//    let calorieCount = HKQuantityType.quantityType(forIdentifier: HKQuantityTypeIdentifier.activeEnergyBurned)
//    let calorieUnit = HKUnit.kilocalorie()
    
}
